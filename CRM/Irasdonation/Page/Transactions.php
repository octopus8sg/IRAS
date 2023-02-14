<?php

use CRM_Irasdonation_ExtensionUtil as E;

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
    $method = 'method';
    $sent_response = 'sent_response';
    $transaction_range_start_date = 'transaction_range_start_date';
    $transaction_range_end_date = 'transaction_range_end_date';
    $sent_range_start_date = 'sent_range_start_date';
    $sent_range_end_date = 'sent_range_end_date';
      $settings = CRM_Irasdonation_Utils::getSettings();
      $prefix = CRM_Utils_Array::value(CRM_Irasdonation_Utils::PREFIX['slug'], $settings);
    $params[$method] = CRM_Utils_Request::retrieveValue($method, 'Positive', null);
    $params[$sent_response] = CRM_Utils_Request::retrieveValue($sent_response, 'Positive', null);
    $params[$transaction_range_start_date] = CRM_Utils_Request::retrieveValue($transaction_range_start_date, 'String', null);
    $params[$transaction_range_end_date] = CRM_Utils_Request::retrieveValue($transaction_range_end_date, 'String', null);
    $params[$sent_range_start_date] = CRM_Utils_Request::retrieveValue($sent_range_start_date, 'String', null);
    $params[$sent_range_end_date] = CRM_Utils_Request::retrieveValue($sent_range_end_date, 'String', null);

    $sortMapper = [
      0 => 'receipt_no',
      1 => 'issued_on',
      2 => 'total_amount',
      3 => 'contact_id',
      4 => 'sort_name',
      5 => 'created_date',
      6 => 'sent_method',
      7 => 'sent_response',
      8 => 'response_body',
    ];

    $sort = isset($_REQUEST['iSortCol_0']) ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
    $sortOrder = isset($_REQUEST['sSortDir_0']) ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';
    $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
    $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);

    $where = " UPPER(cdnlog.receipt_status)='ISSUED' ";

    //0 - offline
    //1 - online
    if ($params[$method] != null) {
      switch ($params[$method]) {
        case 1:
          $where .= " AND cdnlog.id NOT IN (select iras_don.cdntaxreceipts_log_id from civicrm_o8_iras_donation iras_don)";
          break;
        case 2:
          $where .= " AND donation.is_api = 0";
          break;
        case 3:
          $where .= " AND donation.is_api = 1";
          break;
      }
    }

    if ($params[$sent_response] != null) {
      $where .= " AND ilog.response_code = $params[$sent_response]";
    }

    if ($params[$transaction_range_start_date] != null ) {
      $where .= " AND FROM_UNIXTIME(cdnlog.issued_on) >= '$params[$transaction_range_start_date]'";
    }
    if ($params[$transaction_range_end_date] != null) {
      $where .= " AND FROM_UNIXTIME(cdnlog.issued_on) <= '$params[$transaction_range_end_date]'";
    }

    if ($params[$sent_range_start_date] != null ) {
      $where .= " AND donation.created_date >= '$params[$sent_range_start_date]' ";
    }

    if ($params[$sent_range_end_date] != null) {
      $where .= " AND donation.created_date <= '$params[$sent_range_end_date]'";
    }


    $sql = "SELECT cdnlog.id, 
            CONCAT('$prefix', LPAD(RIGHT(cdnlogcontrib.contribution_id, 7), 7, 0)) receipt_no,
            FROM_UNIXTIME(cdnlog.issued_on) issued_on,
            cdnlog.receipt_amount,
            cont.id contact_id,
            cont.sort_name,
            donation.created_date,
            IF(
                donation.is_api IS NULL,
                NULL,
                IF(donation.is_api = 1, 'API', 'Offline')
            ) sent_method,
            IF(
                ilog.response_code IS NULL,
                NULL,
                IF(ilog.response_code = 10, 'Success', 'Fail')
            ) sent_response,
            ilog.response_body,
            cont.external_identifier,
            contrib.receive_date
            FROM cdntaxreceipts_log cdnlog 
            INNER JOIN cdntaxreceipts_log_contributions cdnlogcontrib ON cdnlogcontrib.receipt_id = cdnlog.id
            INNER JOIN civicrm_contribution contrib ON contrib.id = cdnlogcontrib.contribution_id
            INNER JOIN civicrm_contact cont ON cont.id = contrib.contact_id
            INNER JOIN civicrm_financial_type fintype ON fintype.id = contrib.financial_type_id
            LEFT JOIN (
              SELECT id,
                  cdntaxreceipts_log_id,
                  created_date,
                  is_api,
                  log_id
              FROM civicrm_o8_iras_donation
              WHERE id IN ( SELECT MAX(tdon.id) FROM civicrm_o8_iras_donation tdon GROUP BY tdon.cdntaxreceipts_log_id)
            ) donation ON donation.cdntaxreceipts_log_id = cdnlog.id
            LEFT JOIN civicrm_o8_iras_response_log ilog ON ilog.id = donation.log_id
        WHERE $where
            ";

    if ($sort !== NULL) {
      $sql .= " ORDER BY {$sort} {$sortOrder}";
    } else {
      $sql = $sql . ' ORDER BY cdnlog.id DESC';
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
      $sentMessage .= $servResp->info->message;
      if($servResp->info->fieldInfoList != null)
      $sentMessage .= ' > ' . json_encode($servResp->info->fieldInfoList);

      $rows[$count][] = $result->receipt_no;
      $rows[$count][] = $result->issued_on;
      $rows[$count][] = $result->receipt_amount;
      $rows[$count][] = $result->contact_id;
      $rows[$count][] = $result->sort_name;
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
