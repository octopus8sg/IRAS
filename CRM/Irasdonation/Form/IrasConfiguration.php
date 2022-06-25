<?php

use Civi\Api4\IrasDonation;
use CRM_Irasdonation_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Irasdonation_Form_IrasConfiguration extends CRM_Core_Form
{
  public function buildQuickForm()
  {

    $orgTypes = array(
      null => E::ts('- select -'),
      '5' => E::ts('UEN-BUSINESS'),
      '6' => E::ts('UEN-LOCAL CO'),
      'U' => E::ts('UEN-OTHERS'),
      'A' => E::ts('ASGD'),
      'I' => E::ts('ITR')
    );

    $sql =  "SELECT * FROM civicrm_o8_iras_config ic";
    $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);

    $params = array();
    while ($result->fetch()) {
      $params[$result->param_name] = $result->param_value;
    }

    $this->add('text', 'client_id', ts('Client Id'), ['value' => $params['client_id']]);
    $this->add('text', 'client_secret', ts('Client secret'), ['value' => $params['client_secret']]);
    // $this->add('advcheckbox', 'validate_only', ts('Include receipts previously generated'));
    // $this->add('text', 'access_token', ts('Access token'), ['value'=>$params['access_token']]);
    $tmp = $orgTypes[$params['organization_type']];
    $orgTypes[null] = $tmp;

    $this->add(
      'select', // field type
      'organization_type', // field name
      'Organization type', // field label
      $orgTypes, // list of options
      FALSE // is required
    );

    $this->add('text', 'organisation_id', ts('Organization ID number(UEN)'), ['value' => $params['organisation_id']]);
    $this->add('text', 'authorised_person_id', ts('Authorized User ID(SingpassID)'), ['value' => $params['authorised_person_id']]);
    $this->add('text', 'authorised_person_name', ts('Authorized user full name'), ['value' => $params['authorised_person_name']]);
    $this->add('text', 'authorised_person_designation', ts('Authorized user designation'), ['value' => $params['authorised_person_designation']]);
    $this->add('text', 'authorised_person_email', ts('Authorized user email'), ['value' => $params['authorised_person_email']]);

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Save configuration'),
        'isDefault' => TRUE,
      ),
    ));

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess()
  {
    $postedVals = array(
      'client_id' => null,
      'client_secret' => null,
      'organization_type' => null,
      'organisation_id' => null,
      'authorised_person_id' => null,
      'authorised_person_name' => null,
      'authorised_person_designation' => null,
      'authorised_person_email' => null,
    );

    $values = $this->exportValues();
    $postedVals['client_id'] = $values["client_id"];
    $postedVals['client_secret'] = $values["client_secret"];
    $postedVals['organization_type'] = $values["organization_type"];
    $postedVals['organisation_id'] = $values["organisation_id"];
    $postedVals['authorised_person_id'] = $values["authorised_person_id"];
    $postedVals['authorised_person_name'] = $values["authorised_person_name"];
    $postedVals['authorised_person_designation'] = $values["authorised_person_designation"];
    $postedVals['authorised_person_email'] = $values["authorised_person_email"];

    foreach ($postedVals as $key => $value) {
      if ($value == null) {
        CRM_Core_Session::setStatus('All fields are must be filled', ts('Empty field'), 'success', array('expires' => 5000));
        return;
      }
    }

    //if all is ok clear parametrs
    $sql =  "TRUNCATE TABLE civicrm_o8_iras_config";
    CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);

    foreach ($postedVals as $key => $value) {
      $sql =  "INSERT INTO civicrm_o8_iras_config(param_name, param_value) VALUES('$key', '$value')";
      CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);
    }

    // $sql =  "SELECT * FROM civicrm_o8_iras_config ic";
    // $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);

    // $csvData = [];
    // $dataBody = [];

    // $repYear = date("Y");
    // if ($reportDate != null)
    //   $repYear = date("Y", strtotime($reportDate));

    // //generate header of report
    // while ($result->fetch()) {
    //   $dataHead = [0, 7, $repYear, 7, 0, $result->description, null, null, null, null, null, null, null, null];
    //   array_push($csvData, $dataHead);
    // }

    // $inList = '1=1';

    // if ($startDate != null) {
    //   $inList .= " AND trxn.trxn_date >= '$startDate'";
    // }

    // if ($endDate != null) {
    //   $inList .= " AND trxn.trxn_date <= '$endDate'";
    // }

    // if ($startDate == null && $endDate == null) {
    //   if ($reportDate == null) {
    //     if ($includePrevious == FALSE) {
    //       $inList .= " AND trxn.id NOT IN (SELECT ci.financial_trxn_id FROM civicrm_o8_iras_donation ci WHERE ci.created_date IS NOT NULL)";
    //     }
    //   } else {
    //     $inList .= " AND trxn.id IN (SELECT ci.financial_trxn_id FROM civicrm_o8_iras_donation ci WHERE ci.created_date = '$reportDate' AND ci.created_date IS NOT NULL)";
    //   }
    // }

    // $sql = "SELECT 
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

    // $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);
    // $insert = '';
    // $total = 0;
    // $incer = 0;
    // $genDate = date('Y-m-d H:i:s');

    // //generate body of th report
    // while ($result->fetch()) {
    //   $idType = $this->paseUENNumber($result->external_identifier);
    //   if ($idType > 0) {
    //     $dataBody = [1, $idType, $result->external_identifier, str_replace(',', '', $result->sort_name), null, null, null, null, null, $result->total_amount, date("Ymd", strtotime($result->receive_date)), substr($result->trxn_id, 0, 10), 'O', 'Z'];

    //     if ($reportDate == null) {
    //       $insert =  "INSERT INTO civicrm_o8_iras_donation VALUES ($result->id,'$genDate');";
    //       CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);
    //     }

    //     array_push($csvData, $dataBody);
    //     $total += $result->total_amount;
    //     $incer++;
    //   }
    // }

    // //generate buttom line of the report
    // $dataBottom = [2, $incer, $total, null, null, null, null, null, null, null, null, null, null, null];
    // array_push($csvData, $dataBottom);

    // if (count($dataBody) > 0) $this->generateCsv($csvData);
    // else CRM_Core_Session::setStatus('No any data to generate report', ts('All reporta are generated'), 'success', array('expires' => 5000));

    parent::postProcess();
  }

  function paseUENNumber($uen)
  {
    $idTypes = ["nric" => 1, "fin" => 2, "uenb" => 5, "uenl" => 6, "asgd" => 8, "itr" => 10, "ueno" => 35];
    if ($uen == null) return 0;
    switch ($uen) {
      case ($uen[0] == 'S' || $uen[0] == 'T') && is_numeric(substr($uen, 1, 7)):
        return $idTypes['nric'];
      case ($uen[0] == 'F' || $uen[0] == 'G') && is_numeric(substr($uen, 1, 7)):
        return $idTypes['fin'];
      case (strlen($uen) < 10 && is_numeric(substr($uen, 0, 8))):
        return $idTypes['uenb'];
      case (((int)substr($uen, 0, 4)) >= 1800 && ((int)substr($uen, 0, 4)) <= date("Y")) && is_numeric(substr($uen, 4, 5)):
        return $idTypes['uenl'];
      case ($uen[0] == 'A' && is_numeric(substr($uen, 1, 7))):
        return $idTypes['asgd'];
      case (is_numeric(substr($uen, 0, 9))):
        return $idTypes['itr'];
      case (($uen[0] == 'T' || $uen[0] == 'S' || $uen[0] == 'R') && is_numeric(substr($uen, 1, 2)) && !is_numeric(substr($uen, 3, 2)) && is_numeric(substr($uen, 5, 4))):
        return $idTypes['ueno'];
      default:
        return 0;
    }
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames()
  {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
