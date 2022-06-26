<?php

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Irasdonation_Form_IrasOnlineReport 
{

  public function onlineReport(){
    $params['organisation_id']='afg';
      $sql =  "SELECT * FROM civicrm_o8_iras_config ic";
      $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);
      $params = array();
      while ($result->fetch()) {
        $params[$result->param_name] = $result->param_value;
      }

      return $params['organisation_id'];


    //   $csvData = [];
    //   $dataBody = [];

    //   $repYear = date("Y");
    //   if ($reportDate != null)
    //     $repYear = date("Y", strtotime($reportDate));

    //   if ($startDate != null)
    //     $repYear = date("Y", strtotime($startDate));

    //   //generate header of report   
    //   $dataHead = [0, 7, $repYear, 7, 0, $params['organisation_id'], null, null, null, null, null, null, null, null];
    //   array_push($csvData, $dataHead);

    //   if (empty($params['organisation_id'])) {
    //     CRM_Core_Session::setStatus('Please configure extension before using', ts('Extension configuration'), 'warning', array('expires' => 5000));
    //     return;
    //   }

    //   if (($startDate != null && $endDate == null) || ($startDate == null && $endDate != null)) {
    //     CRM_Core_Session::setStatus('Please select date range', ts('Date range incorrect'), 'warning', array('expires' => 5000));
    //     return;
    //   }

    //   $inList = '1=1';

    //   if ($startDate != null && $endDate != null) {
    //     if ($includePrevious == 0) {
    //       $inList .= " AND trxn.id NOT IN (SELECT ci.financial_trxn_id FROM civicrm_o8_iras_donation ci WHERE ci.created_date IS NOT NULL) AND trxn.trxn_date >= '$startDate'  AND trxn.trxn_date <= '$endDate'";
    //     } else {
    //       $inList .= " AND trxn.trxn_date >= '$startDate' AND trxn.trxn_date <= '$endDate'";
    //     }
    //   } else {
    //     if ($reportDate != null) {
    //       $inList .= " AND trxn.id IN (SELECT ci.financial_trxn_id FROM civicrm_o8_iras_donation ci WHERE ci.created_date = '$reportDate' AND ci.created_date IS NOT NULL)";
    //     } else {
    //       $inList .= " AND trxn.id NOT IN (SELECT ci.financial_trxn_id FROM civicrm_o8_iras_donation ci WHERE ci.created_date IS NOT NULL AND ci.created_date like'%$repYear%')";
    //     }
    //   }

    //   $sql = "SELECT 
    // trxn.id, 
    // cont.sort_name, 
    // cont.external_identifier,
    // trxn.total_amount,
    // contrib.trxn_id,
    // trxn.trxn_date,
    // contrib.receive_date
    // FROM civicrm_financial_trxn trxn 
    // INNER JOIN civicrm_contribution contrib ON contrib.trxn_id = trxn.trxn_id  
    // INNER JOIN civicrm_contact cont ON cont.id = contrib.contact_id 
    // INNER JOIN civicrm_financial_type fintype ON fintype.id = contrib.financial_type_id   
    // WHERE $inList
    // AND trxn.status_id = 1 AND fintype.is_deductible = 1
    // AND cont.external_identifier IS NOT NULL 
    // LIMIT 5000";

    //   $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);
    //   $insert = '';
    //   $total = 0;
    //   $incer = 0;
    //   $genDate = date('Y-m-d H:i:s');

    //   //generate body of th report
    //   while ($result->fetch()) {
    //     $idType = $this->parsUENNumber($result->external_identifier);
    //     if ($idType > 0) {
    //       $dataBody = [1, $idType, $result->external_identifier, str_replace(',', '', $result->sort_name), null, null, null, null, null, $result->total_amount, date("Ymd", strtotime($result->receive_date)), substr($result->trxn_id, 0, 10), 'O', 'Z'];

    //       if ($reportDate == null) {
    //         $insert = "INSERT INTO civicrm_o8_iras_donation VALUES ($result->id,'$genDate');";
    //         CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);
    //       }

    //       array_push($csvData, $dataBody);
    //       $total += $result->total_amount;
    //       $incer++;
    //     }
    //   }

    //   //generate buttom line of the report
    //   $dataBottom = [2, $incer, $total, null, null, null, null, null, null, null, null, null, null, null];
    //   array_push($csvData, $dataBottom);

    //   if (count($dataBody) > 0) $this->generateCsv($csvData);
    //   else CRM_Core_Session::setStatus('No any data to generate report', ts('All reports are generated'), 'success', array('expires' => 5000));

  }
 }
