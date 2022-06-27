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
      $dataHead = [0, 7, $repYear, 7, 0, $params['organisation_id'], null, null, null, null, null, null, null, null];
      array_push($csvData, $dataHead);

      $inList = " trxn.id NOT IN (SELECT ci.financial_trxn_id FROM civicrm_o8_iras_donation ci WHERE ci.created_date IS NOT NULL AND YEAR(ci.created_date) like $repYear)";

      $sql = "SELECT 
      trxn.id, 
      cont.sort_name, 
      cont.external_identifier,
      trxn.total_amount,
      contrib.trxn_id,
      trxn.trxn_date,
      contrib.receive_date
      FROM civicrm_financial_trxn trxn 
      INNER JOIN civicrm_contribution contrib ON contrib.trxn_id = trxn.trxn_id  
      INNER JOIN civicrm_contact cont ON cont.id = contrib.contact_id 
      INNER JOIN civicrm_financial_type fintype ON fintype.id = contrib.financial_type_id   
      WHERE $inList
      AND trxn.status_id = 1 AND fintype.is_deductible = 1
      AND cont.external_identifier IS NOT NULL 
      LIMIT 5000";

      $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);
      $insert = '';
      $total = 0;
      $incer = 0;
      $genDate = date('Y-m-d H:i:s');

      //generate body of th report
      $details = array();
      while ($result->fetch()) {
        $idType = $this->parsUENNumber($result->external_identifier);
        if ($idType > 0) {
          $dataBody = [1, $idType, $result->external_identifier, str_replace(',', '', $result->sort_name), null, null, null, null, null, $result->total_amount, date("Ymd", strtotime($result->receive_date)), substr($result->trxn_id, 0, 10), 'O', 'Z'];

          $insert .= "INSERT INTO civicrm_o8_iras_donation VALUES ($result->id,'$genDate');";

          array_push($csvData, $dataBody);
          $total += $result->total_amount;
          $incer++;
        }
      }
      
      //prepare body

      $body = array(
        'orgAndSubmissionInfo'=> [
          'validateOnly'=>'true',
          'basisYear'=>"$repYear",
          'organisationIDType'=>$params['organization_type'],
          'organisationIDNo'=>$params['organisation_id'],
          'organisationName'=>$params['organisation_name'],
          'batchIndicator'=>'O',
          'authorisedPersonIDNo'=>$params['authorised_person_id'],
          'authorisedPersonName'=>$params['authorised_person_name'],
          'authorisedPersonDesignation'=>$params['authorised_person_designation'],
          'telephone'=>$params['authorised_person_phone'],
          'authorisedPersonEmail'=>$params['authorised_person_email'],
          'numOfRecords'=>'2',
          'totalDonationAmount'=>'5000'
        ],
        "donationDonorDtl"=>[

        ]
      );
      // //generate buttom line of the report
      // $dataBottom = [2, $incer, $total, null, null, null, null, null, null, null, null, null, null, null];
      // array_push($csvData, $dataBottom);

      $reponse = $this->curl_post($url, $header, $body);

      CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);

      // $insert = "INSERT INTO civicrm_o8_iras_response_log VALUES ('".json_encode($reponse)."','$genDate');";
      // CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);
      
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
