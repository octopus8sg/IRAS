<?php

use Civi\Api4\IrasDonation;
use CRM_Irasdonation_ExtensionUtil as E;
use CRM_Irasdonation_Utils as U;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Irasdonation_Form_IrasConfiguration extends CRM_Core_Form
{
    public function buildQuickForm()
    {

        $this->add('checkbox', U::SAVE_LOG['slug'], U::SAVE_LOG['name']);
        $this->add('text', U::CLIENT_ID['slug'], U::CLIENT_ID['name']);
        $this->add('text', U::CLIENT_SECRET['slug'], U::CLIENT_SECRET['name']);

        $types = U::TYPES;
        $this->add(
            'select', // field type
            U::ORGANIZATION_TYPE['slug'], // field name
            U::ORGANIZATION_TYPE['name'], // field label
            $types, // list of options
            TRUE // is required
        );

        $this->add('text', U::ORGANISATION_ID['slug'], U::ORGANISATION_ID['name'], null, TRUE);
        $this->add('text', U::ORGANISATION_NAME['slug'], U::ORGANISATION_NAME['name'], null, TRUE);
        $this->add('text', U::AUTHORISED_PERSON_ID['slug'], U::AUTHORISED_PERSON_ID['name']);
        $this->add('text', U::AUTHORISED_PERSON_NAME['slug'], U::AUTHORISED_PERSON_NAME['name']);
        $this->add('text', U::AUTHORISED_PERSON_DESIGNATION['slug'], U::AUTHORISED_PERSON_DESIGNATION['name']);
        $this->add('text', U::AUTHORISED_PERSON_PHONE['slug'], U::AUTHORISED_PERSON_PHONE['name']);
        $this->add('text', U::AUTHORISED_PERSON_EMAIL['slug'], U::AUTHORISED_PERSON_EMAIL['name']);
        $this->add('text', U::REPORT_URL['slug'], U::REPORT_URL['name']);
        $this->add('text', U::MIN_AMOUNT['slug'], U::MIN_AMOUNT['name']);

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

    public function setDefaultValues()
    {
        $defaults = [];
        $settings = CRM_Core_BAO_Setting::getItem(U::SETTINGS_NAME, U::SETTINGS_SLUG);
        U::writeLog($settings, "starting values");
        if (!empty($settings)) {
            $defaults = $settings;
        }

        return $defaults;
    }

    public function postProcess()
    {
        $postedVals = array(
            U::SAVE_LOG['slug'] => 1,
            U::CLIENT_ID['slug'] => null,
            U::CLIENT_SECRET['slug'] => null,
            U::ORGANIZATION_TYPE['slug'] => null,
            U::ORGANISATION_ID['slug'] => null,
            U::ORGANISATION_NAME['slug'] => null,
            U::AUTHORISED_PERSON_ID['slug'] => null,
            U::AUTHORISED_PERSON_NAME['slug'] => null,
            U::AUTHORISED_PERSON_DESIGNATION['slug'] => null,
            U::AUTHORISED_PERSON_PHONE['slug'] => null,
            U::AUTHORISED_PERSON_EMAIL['slug'] => null,
            U::REPORT_URL['slug'] => null,
            U::MIN_AMOUNT['slug'] => null
        );

        $values = $this->exportValues();
        $postedVals[U::SAVE_LOG['slug']] = $values[U::SAVE_LOG['slug']];
        $postedVals[U::CLIENT_ID['slug']] = $values[U::CLIENT_ID['slug']];
        $postedVals[U::CLIENT_SECRET['slug']] = $values[U::CLIENT_SECRET['slug']];
        $postedVals[U::ORGANIZATION_TYPE['slug']] = $values[U::ORGANIZATION_TYPE['slug']];
        $postedVals[U::ORGANISATION_ID['slug']] = $values[U::ORGANISATION_ID['slug']];
        $postedVals[U::ORGANISATION_NAME['slug']] = $values[U::ORGANISATION_NAME['slug']];
        $postedVals[U::AUTHORISED_PERSON_ID['slug']] = $values[U::AUTHORISED_PERSON_ID['slug']];
        $postedVals[U::AUTHORISED_PERSON_NAME['slug']] = $values[U::AUTHORISED_PERSON_NAME['slug']];
        $postedVals[U::AUTHORISED_PERSON_DESIGNATION['slug']] = $values[U::AUTHORISED_PERSON_DESIGNATION['slug']];
        $postedVals[U::AUTHORISED_PERSON_PHONE['slug']] = $values[U::AUTHORISED_PERSON_PHONE['slug']];
        $postedVals[U::AUTHORISED_PERSON_EMAIL['slug']] = $values[U::AUTHORISED_PERSON_EMAIL['slug']];
        $postedVals[U::REPORT_URL['slug']] = $values[U::REPORT_URL['slug']];
        $postedVals[U::MIN_AMOUNT['slug']] = $values[U::MIN_AMOUNT['slug']];

        $checkFields = array(
            U::ORGANISATION_ID['slug'] => U::ORGANISATION_ID['name'],
            U::ORGANIZATION_TYPE['slug'] => U::ORGANIZATION_TYPE['name'],
            U::ORGANISATION_NAME['slug'] => U::ORGANISATION_NAME['name']);

        foreach ($postedVals as $key => $value) {
            if (in_array($key, array_keys($checkFields)) && $value == null) {
                CRM_Core_Session::setStatus("\"" . $checkFields[$key] . "\" field is required", ts('Empty field'), 'warning', array('expires' => 5000));
                return;
            }
        }

        if ($this->parsUENNumber($postedVals[U::ORGANISATION_ID['slug']]) == 0) {
            CRM_Core_Session::setStatus('Incorrect organization ID(UEN)', ts('Incorrect UEN'), 'warning', array('expires' => 5000));
            return;
        }
        //if all is ok clear parametrs
        $sql = "TRUNCATE TABLE civicrm_o8_iras_config";
        CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);

        $s = CRM_Core_BAO_Setting::setItem($postedVals, U::SETTINGS_NAME, U::SETTINGS_SLUG);
        U::writeLog($s);

        CRM_Core_Session::setStatus('Configuration saved successfully', ts('Success'), 'success', array('expires' => 5000));

        parent::postProcess();
    }

    function parsUENNumber($uen)
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
