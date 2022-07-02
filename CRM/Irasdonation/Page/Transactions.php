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

    $params[$method] = CRM_Utils_Request::retrieveValue($method, 'Positive', 0);
    $params[$sent_response] = CRM_Utils_Request::retrieveValue($sent_response, 'Positive', 0);

    $params[$transaction_range_start_date] = CRM_Utils_Request::retrieveValue($transaction_range_start_date, 'String', null);
    try {
      $params[$transaction_range_start_date] = new DateTime($params[$transaction_range_start_date]);
    } catch (Exception $e) {
      $params[$transaction_range_start_date] = null;
    }

    $params[$transaction_range_end_date] = CRM_Utils_Request::retrieveValue($transaction_range_end_date, 'String', null);
    try {
      $params[$transaction_range_end_date] = new DateTime($transaction_range_end_date);
    } catch (Exception $e) {
      $params[$transaction_range_end_date] = null;
    }

    $params[$sent_range_start_date] = CRM_Utils_Request::retrieveValue($sent_range_start_date, 'String', null);
    try {
      $params[$sent_range_start_date] = new DateTime($sent_range_start_date);
    } catch (Exception $e) {
      $params[$sent_range_start_date] = null;
    }

    $params[$sent_range_end_date] = CRM_Utils_Request::retrieveValue($sent_range_end_date, 'String', null);
    try {
      $params[$sent_range_end_date] = new DateTime($sent_range_end_date);
    } catch (Exception $e) {
      $params[$sent_range_end_date] = null;
    }

    $hmdatas = [
      'data' => json_encode($params),
      'recordsTotal' => 0,
      'recordsFiltered' => 0,
    ];
    CRM_Utils_JSON::output($hmdatas);
  }
}
