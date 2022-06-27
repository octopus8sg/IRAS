<?php
if (!class_exists('CRM_IrasOnlineReport')) {
  class CRM_IrasOnlineReport
  {
    public function onlineReport()
    {
      $params['organisation_id'] = 'afg';
      $sql =  "SELECT * FROM civicrm_o8_iras_config ic";
      $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);

      $params = array();
      while ($result->fetch()) {
        $params[$result->param_name] = $result->param_value;
      }

      $receipant = [];
      $dataBody = [];

      $repYear = date("Y");


      //get reporting url
      $url = $params['report_url'];

      $client_id = $params['client_id'];
      $client_secret = $params['client_secret'];

      //prepare header      
      $header = [
        "Accept: application/json",
        "charset: UTF-8",
        "Content-Type: application/json",
        "X-IBM-Client-Id: $client_id",
        "X-IBM-Client-Secret: $client_secret",
        "access_token: ",
      ];


      //generate header of report   
      $inList = " trxn.id NOT IN (SELECT ci.financial_trxn_id FROM civicrm_o8_iras_donation ci WHERE ci.created_date IS NOT NULL AND YEAR(ci.created_date) like $repYear)";

      $sql = "SELECT 
      trxn.id, 
      cont.sort_name, 
      cont.external_identifier,
      addr.supplemental_address_1,
      addr.supplemental_address_2,
      addr.postal_code,
      trxn.total_amount,
      contrib.trxn_id,
      trxn.trxn_date,
      contrib.receive_date
      FROM civicrm_financial_trxn trxn 
      INNER JOIN civicrm_contribution contrib ON contrib.trxn_id = trxn.trxn_id  
      INNER JOIN civicrm_contact cont ON cont.id = contrib.contact_id 
      INNER JOIN civicrm_financial_type fintype ON fintype.id = contrib.financial_type_id   
      LEFT JOIN civicrm_address addr ON addr.id = cont.addressee_id
      WHERE $inList
      AND trxn.status_id = 1 AND fintype.is_deductible = 1
      AND cont.external_identifier IS NOT NULL 
      LIMIT 5000";

      $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);
      $insert = '';
      $total = 0;
      $incer = 0;
      $genDate = date('Y-m-d H:i:s');

      $saveReport = array();
      //generate body of th report
      $details = array();
      while ($result->fetch()) {
        $config = new CRM_Irasdonation_Form_IrasConfiguration();
        $idType = $config->parsUENNumber($result->external_identifier);
        if ($idType > 0) {
          $dataBody = array(
            'recordID' => $incer + 1,
            'idType' => $idType,
            'idNumber' => $result->external_identifier,
            'individualIndicator' => '',
            'name' => $result->sort_name,
            'addressLine1' => $result->supplemental_address_1,
            'addressLine2' => $result->supplemental_address_2,
            'postalCode' => '',
            'donationAmount' => round($result->total_amount),
            'dateOfDonation' => date("Ymd", strtotime($result->receive_date)),
            'receiptNum' => substr($result->trxn_id, 0, 10),
            'typeOfDonation' => 'O',
            'namingDonation' => 'Z'
          );

          array_push($saveReport, $result->id);

          array_push($details, $dataBody);
          $total += $result->total_amount;
          $incer++;
        }
      }

      //prepare body
      $body = array(
        'orgAndSubmissionInfo' => [
          'validateOnly' => 'true',
          'basisYear' => $repYear,
          'organisationIDType' => $params['organization_type'],
          'organisationIDNo' => $params['organisation_id'],
          'organisationName' => $params['organisation_name'],
          'batchIndicator' => 'O',
          'authorisedPersonIDNo' => $params['authorised_person_id'],
          'authorisedPersonName' => $params['authorised_person_name'],
          'authorisedPersonDesignation' => $params['authorised_person_designation'],
          'telephone' => $params['authorised_person_phone'],
          'authorisedPersonEmail' => $params['authorised_person_email'],
          'numOfRecords' => $incer,
          'totalDonationAmount' => $total
        ],
        "donationDonorDtl" => $details
      );

      $reponse = $this->curl_post($url, $header, $body);

      //CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);

      if ($reponse->returnCode == 10)
      {
        foreach ($saveReport as $value) {
          $insert = "INSERT INTO civicrm_o8_iras_donation VALUES ($value,'$genDate');";
          CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);
        }
      }

      $insert = "INSERT INTO civicrm_o8_iras_response_log VALUES ('" . json_encode($reponse) . "','$genDate');";
      CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);

      return $reponse;
    }

    function curl_post($url, $header, $body)
    {
      $c_type = '';
      if (!is_null($header)) {
        foreach ($header as $item) {
          $row = explode(':', $item);
          if (strcmp(strtolower(trim($row[0])), 'content-type') == 0) {
            $c_type = trim($row[1]);
          }
        }
        switch ($c_type) {
          case 'application/x-www-form-urlencoded':
            $content_body = http_build_query($body);
            break;
          case 'application/json':
            $content_body = json_encode($body);
            break;
        }
      } else {
        $header = array();
      }

      $curlOptions = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_VERBOSE => TRUE,
        CURLOPT_STDERR => $verbose = fopen('php://temp', 'rw+'),
        CURLOPT_FILETIME => TRUE,
        CURLOPT_POST => TRUE,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_POSTFIELDS => $content_body
      );
      $curl = curl_init();
      curl_setopt_array($curl, $curlOptions);
      $response = curl_exec($curl);
      curl_close($curl);

      return json_decode($response);
    }
  }
}
