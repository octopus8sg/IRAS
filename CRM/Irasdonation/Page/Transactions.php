<?php

use CRM_Irasdonation_ExtensionUtil as E;
use CRM_Irasdonation_Utils as U;

class CRM_Irasdonation_Page_Transactions extends CRM_Core_Page
{

    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Transactions report'));

        // link for datatables
        $urlQry['snippet'] = 4;
        $transactions_ajax_url = CRM_Utils_System::url('civicrm/irasdonation/transactions_ajax', $urlQry, FALSE, NULL, FALSE);
        $sourceUrl['transactions_ajax_url'] = $transactions_ajax_url;
        $this->assign('useAjax', true);
        CRM_Core_Resources::singleton()->addVars('source_url', $sourceUrl);

        // controller form for ajax search
        $controller_data = new CRM_Core_Controller_Simple(
            'CRM_Irasdonation_Form_TransactionsFilter',
            ts('Transactions filter'),
            NULL,
            FALSE,
            FALSE,
            TRUE
        );

        $controller_data->setEmbedded(TRUE);
        $controller_data->run();

        parent::run();
    }

    public function getAjax()
    {

        $settings = CRM_Irasdonation_Utils::getSettings();
        $min_amount = CRM_Utils_Array::value(CRM_Irasdonation_Utils::MIN_AMOUNT['slug'], $settings);
        $prefix = CRM_Utils_Array::value(CRM_Irasdonation_Utils::PREFIX['slug'], $settings);
        $method = CRM_Utils_Request::retrieveValue('method', 'Positive', null);
        $sent_response = CRM_Utils_Request::retrieveValue('sent_response', 'Positive', null);
        $firstDayOfYear = date('Y-01-01');
        $lastDayOfYear = date('Y-12-31');

        $transaction_range_start_date = CRM_Utils_Request::retrieveValue('transaction_range_start_date', 'String', $firstDayOfYear);
        $transaction_range_end_date = CRM_Utils_Request::retrieveValue('transaction_range_end_date', 'String', $lastDayOfYear);
        $sent_range_start_date = CRM_Utils_Request::retrieveValue('sent_range_start_date', 'String', null);
        $sent_range_end_date = CRM_Utils_Request::retrieveValue('sent_range_end_date', 'String', null);
        $transaction_range_start_date = $transaction_range_start_date ? $transaction_range_start_date : $firstDayOfYear;
        $transaction_range_end_date = $transaction_range_end_date ? $transaction_range_end_date : $lastDayOfYear;
        U::writeLog($transaction_range_start_date, "transaction_range_start_date");
        U::writeLog($firstDayOfYear, "firstDayOfYear");
        $sortMapper = [
            0 => 'receipt_no',
            1 => 'issued_on',
            2 => 'total_amount',
            3 => 'contact_id',
            4 => 'sort_name',
            5 => 'nricuen',
            6 => 'created_date',
            7 => 'sent_method',
            8 => 'sent_response',
            9 => 'response_body',
        ];

        $sort = isset($_REQUEST['iSortCol_0']) ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
        $sortOrder = isset($_REQUEST['sSortDir_0']) ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';
        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
        //Select contacts with external ID
        $completed = U::getContributionStatusID('Completed');
        $where = " contact.external_identifier IS NOT NULL AND contribution.contribution_status_id = $completed and fintype.is_deductible = 1";
        //0 - offline
        //1 - online
        if ($method != null) {
            switch ($method) {
                case 1:
                    $where .= " AND donation.id NOT IN (select donation_log.iras_donation_id from civicrm_o8_iras_donation_log donation_log)";
                    break;
                case 2:
                    $where .= " AND response_log.is_api = 0";
                    break;
                case 3:
                    $where .= " AND response_log.is_api = 1";
                    break;
            }
        }

        if ($sent_response != null) {
            $where .= " AND response_log.response_code = $sent_response";
        }

        if ($transaction_range_start_date != null) {
            $where .= " AND contribution.receive_date >= '$transaction_range_start_date'";
        }

        if ($transaction_range_end_date != null) {
            $where .= " AND contribution.receive_date <= '$transaction_range_end_date'";
        }

        if ($sent_range_start_date != null) {
            $where .= " AND donation.created_date >= '$sent_range_start_date' ";
        }

        if ($sent_range_end_date != null) {
            $where .= " AND donation.created_date <= '$sent_range_end_date'";
        }

        if ($min_amount != null) {
            $where .= " AND contribution.total_amount >= $min_amount";
        }

        $sql = "SELECT contribution.id,
       CONCAT('$prefix', LPAD(RIGHT(contribution.id, 7), 7, 0)) receipt_no,
       contribution.receive_date issued_on,
       contribution.total_amount receipt_amount,
       contact.id contact_id,
       contact.sort_name,
       contact.external_identifier nricuen,
       donation.created_date,
       IF(
               response_log.is_api IS NULL,
               NULL,
               IF(response_log.is_api = 1, 'API', 'Offline')
           ) sent_method,
       IF(
               response_log.response_code IS NULL,
               NULL,
               IF(response_log.response_code = 10, 'Success', 'Fail')
           ) sent_response,
       response_log.response_body,
       contact.external_identifier,
       contribution.receive_date
       FROM civicrm_contribution contribution
         INNER JOIN civicrm_contact contact ON contact.id = contribution.contact_id
         INNER JOIN civicrm_financial_type fintype ON fintype.id = contribution.financial_type_id
         LEFT JOIN civicrm_o8_iras_donation donation ON contribution.id = donation.contribution_id
         LEFT JOIN civicrm_o8_iras_donation_log donation_log ON donation_log.id = donation.last_donation_log_id
         LEFT JOIN civicrm_o8_iras_response_log response_log ON response_log.id = donation_log.iras_response_id
        WHERE $where
            ";

        if ($sort !== NULL) {
            $sql .= " ORDER BY {$sort} {$sortOrder}";
        } else {
            $sql = $sql . ' ORDER BY donation.id DESC';
        }

        if ($limit !== false) {
            if ($limit !== NULL) {
                if ($offset !== false) {
                    if ($offset !== NULL) {
                        $sql .= " LIMIT {$offset}, {$limit}";
                    }
                }
            }
        }

        $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);

        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($result->fetch()) {
            $sentMessage = '';
            $servResp = json_decode($result->response_body);
            if (isset($servResp->data)) {
                if (isset($servResp->data->acknowledgementCode)) {
                    $sentMessage .= "Success code > " . $servResp->data->acknowledgementCode . "\n";

                }
            }
            $sentMessage .= $servResp->info->message;
            if ($servResp->info->fieldInfoList != null)
                $sentMessage .= ' > ' . json_encode($servResp->info->fieldInfoList);

            $rows[$count][] = $result->receipt_no;
            $rows[$count][] = $result->issued_on;
            $rows[$count][] = $result->receipt_amount;
            $rows[$count][] = $result->contact_id;
            $rows[$count][] = $result->sort_name;
            $rows[$count][] = $result->nricuen;
            $rows[$count][] = $result->created_date;
            $rows[$count][] = $result->sent_method;
            $rows[$count][] = $result->sent_response;
            $rows[$count][] = $sentMessage;
            $count++;
        }

        $searchRows = $rows;
        $iTotal = 0;
        if (is_countable($searchRows)) {
            $iTotal = count($searchRows);
        }

        $hmdatas = [
            'data' => $searchRows,
            'recordsTotal' => $iTotal,
            'recordsFiltered' => $iFilteredTotal,
        ];

        CRM_Utils_JSON::output($hmdatas);
    }
}
