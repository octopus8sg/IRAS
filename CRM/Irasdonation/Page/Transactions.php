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

    $params[$method] = CRM_Utils_Request::retrieveValue($method, 'Positive', null);
    $params[$sent_response] = CRM_Utils_Request::retrieveValue($sent_response, 'Positive', null);
    $params[$transaction_range_start_date] = CRM_Utils_Request::retrieveValue($transaction_range_start_date, 'String', null);
    $params[$transaction_range_end_date] = CRM_Utils_Request::retrieveValue($transaction_range_end_date, 'String', null);
    $params[$sent_range_start_date] = CRM_Utils_Request::retrieveValue($sent_range_start_date, 'String', null);
    $params[$sent_range_end_date] = CRM_Utils_Request::retrieveValue($sent_range_end_date, 'String', null);


    $sql = "SELECT 
      trxn.id, 
      RIGHT(contrib.trxn_id, 10) as trxn_id,
      trxn.trxn_date,
      trxn.total_amount,
      cont.id,
      cont.sort_name, 
      donation.edited_date,
      IF(donation.is_api IS NULL, '', IF(donation.is_api = 1, 'API', 'Offline')) method,
      IF(ilog.response_code IS NULL, '', IF(ilog.response_code = 10, 'Success', 'Fail')) response,
      ilog.response_body,
      cont.external_identifier,
      contrib.receive_date
      FROM civicrm_financial_trxn trxn 
      INNER JOIN civicrm_contribution contrib ON contrib.trxn_id = trxn.trxn_id  
      INNER JOIN civicrm_contact cont ON cont.id = contrib.contact_id 
      INNER JOIN civicrm_financial_type fintype ON fintype.id = contrib.financial_type_id   
      LEFT JOIN civicrm_o8_iras_donation donation ON donation.financial_trxn_id = trxn.id
      LEFT JOIN civicrm_o8_iras_response_log ilog ON ilog.id = donation.log_id

      AND trxn.status_id = 1 AND fintype.is_deductible = 1
      AND cont.external_identifier IS NOT NULL 
      LIMIT 5000;";


    $hmdatas = [
      'data' => null,
      'recordsTotal' => 0,
      'recordsFiltered' => 0,
    ];
    CRM_Utils_JSON::output($hmdatas);
  }
}
