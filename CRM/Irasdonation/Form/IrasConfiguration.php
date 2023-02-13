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

        $textsize = ['size' => 65];
        $this->add('checkbox', U::SAVE_LOG['slug'], U::SAVE_LOG['name']);
        $this->add('static', U::SAVE_LOG['slug'] . "_description", U::SAVE_LOG['slug'], U::SAVE_LOG['description']);

        $this->add('checkbox', U::VALIDATE_ONLY['slug'], U::VALIDATE_ONLY['name']);
        $this->add('static', U::VALIDATE_ONLY['slug'] . "_description", U::VALIDATE_ONLY['slug'], U::VALIDATE_ONLY['description']);

        $this->add('text', U::REPORT_URL['slug'], U::REPORT_URL['name'], $textsize);
        $this->add('static', U::REPORT_URL['slug'] . "_description", U::REPORT_URL['slug'], U::REPORT_URL['description']);

        $this->add('text', U::CALLBACK_URL['slug'], U::CALLBACK_URL['name'], $textsize);
        $this->add('static', U::CALLBACK_URL['slug'] . "_description", U::CALLBACK_URL['slug'], U::CALLBACK_URL['description']);


        $this->add('text', U::CLIENT_ID['slug'], U::CLIENT_ID['name'], $textsize, true);
        $this->add('static', U::CLIENT_ID['slug'] . "_description", U::CLIENT_ID['slug'], U::CLIENT_ID['description']);

        $this->add('text', U::CLIENT_SECRET['slug'], U::CLIENT_SECRET['name'], $textsize, TRUE);
        $this->add('static', U::CLIENT_SECRET['slug'] . "_description", U::CLIENT_SECRET['slug'], U::CLIENT_SECRET['description']);

        $types = U::TYPES;
        $this->add('select', // field type
            U::ORGANIZATION_TYPE['slug'], // field name
            U::ORGANIZATION_TYPE['name'], // field label
            [
                '' => ts('- Select Org Type -'),
            ] + $types, // list of options
            TRUE // is required
        );
        $this->add('static', U::ORGANIZATION_TYPE['slug'] . "_description", U::ORGANIZATION_TYPE['slug'], U::ORGANIZATION_TYPE['description']);

        $this->add('text', U::ORGANISATION_ID['slug'], U::ORGANISATION_ID['name'], $textsize, TRUE);
        $this->add('static', U::ORGANISATION_ID['slug'] . "_description", U::ORGANISATION_ID['slug'], U::ORGANISATION_ID['description']);

        $this->add('text', U::ORGANISATION_NAME['slug'], U::ORGANISATION_NAME['name'], $textsize, TRUE);
        $this->add('static', U::ORGANISATION_NAME['slug'] . "_description", U::ORGANISATION_NAME['slug'], U::ORGANISATION_NAME['description']);

        $this->add('text', U::AUTHORISED_PERSON_ID['slug'], U::AUTHORISED_PERSON_ID['name'], $textsize);
        $this->add('static', U::AUTHORISED_PERSON_ID['slug'] . "_description",
            U::AUTHORISED_PERSON_ID['slug'], U::AUTHORISED_PERSON_ID['description']);

        $this->add('text', U::AUTHORISED_PERSON_NAME['slug'], U::AUTHORISED_PERSON_NAME['name'], $textsize);
        $this->add('static', U::AUTHORISED_PERSON_NAME['slug'] . "_description",
            U::AUTHORISED_PERSON_NAME['slug'], U::AUTHORISED_PERSON_NAME['description']);

        $this->add('text', U::AUTHORISED_PERSON_DESIGNATION['slug'], U::AUTHORISED_PERSON_DESIGNATION['name'], $textsize);
        $this->add('static', U::AUTHORISED_PERSON_DESIGNATION['slug'] . "_description",
            U::AUTHORISED_PERSON_DESIGNATION['slug'], U::AUTHORISED_PERSON_DESIGNATION['description']);

        $this->add('text', U::AUTHORISED_PERSON_PHONE['slug'], U::AUTHORISED_PERSON_PHONE['name'], $textsize);
        $this->add('static', U::AUTHORISED_PERSON_PHONE['slug'] . "_description",
            U::AUTHORISED_PERSON_PHONE['slug'], U::AUTHORISED_PERSON_PHONE['description']);

        $this->add('text', U::AUTHORISED_PERSON_EMAIL['slug'], U::AUTHORISED_PERSON_EMAIL['name'], $textsize);
        $this->add('static', U::AUTHORISED_PERSON_EMAIL['slug'] . "_description",
            U::AUTHORISED_PERSON_EMAIL['slug'], U::AUTHORISED_PERSON_EMAIL['description']);
/* //If custom fields are needed to be used
        $contributionStringCustomFields = U::getContributionStringCustomFields();
        $contributionDateCustomFields = U::getContributionDateCustomFields();
        $this->add('select', U::RECIEPT_ID['slug'], U::RECIEPT_ID['name'], [
                '' => ts('- Select Reciept Id Custom Field -'),
            ] + $contributionStringCustomFields);
        $this->add('static', U::RECIEPT_ID['slug'] . "_description", U::RECIEPT_ID['slug'], U::RECIEPT_ID['description'], $textsize);

        $this->add('select', U::RECIEPT_DATE['slug'], U::RECIEPT_DATE['name'], [
                '' => ts('- Select Reciept Date Custom Field -'),
            ] + $contributionDateCustomFields);
        $this->add('static', U::RECIEPT_DATE['slug'] . "_description", U::RECIEPT_DATE['slug'], U::RECIEPT_DATE['description'], $textsize);
*/
        $this->add('text', U::MIN_AMOUNT['slug'], U::MIN_AMOUNT['name'], $textsize);
        $this->add('static', U::MIN_AMOUNT['slug'] . "_description", U::MIN_AMOUNT['slug'], U::MIN_AMOUNT['description']);


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
        $postedVals = [
            U::SAVE_LOG['slug'] => 1,
            U::CLIENT_ID['slug'] => null,
            U::CLIENT_SECRET['slug'] => null,
//            U::RECIEPT_ID['slug'] => null,
//            U::RECIEPT_DATE['slug'] => null,
            U::ORGANIZATION_TYPE['slug'] => null,
            U::ORGANISATION_ID['slug'] => null,
            U::ORGANISATION_NAME['slug'] => null,
            U::AUTHORISED_PERSON_ID['slug'] => null,
            U::AUTHORISED_PERSON_NAME['slug'] => null,
            U::AUTHORISED_PERSON_DESIGNATION['slug'] => null,
            U::AUTHORISED_PERSON_PHONE['slug'] => null,
            U::AUTHORISED_PERSON_EMAIL['slug'] => null,
            U::REPORT_URL['slug'] => null,
            U::CALLBACK_URL['slug'] => null,
            U::VALIDATE_ONLY['slug'] => null,
            U::MIN_AMOUNT['slug'] => null
        ];

        $values = $this->exportValues();
        $postedVals[U::SAVE_LOG['slug']] = $values[U::SAVE_LOG['slug']];
        $postedVals[U::CLIENT_ID['slug']] = $values[U::CLIENT_ID['slug']];
        $postedVals[U::CLIENT_SECRET['slug']] = $values[U::CLIENT_SECRET['slug']];
//        $postedVals[U::RECIEPT_ID['slug']] = $values[U::RECIEPT_ID['slug']];
//        $postedVals[U::RECIEPT_DATE['slug']] = $values[U::RECIEPT_DATE['slug']];
        $postedVals[U::ORGANIZATION_TYPE['slug']] = $values[U::ORGANIZATION_TYPE['slug']];
        $postedVals[U::ORGANISATION_ID['slug']] = $values[U::ORGANISATION_ID['slug']];
        $postedVals[U::ORGANISATION_NAME['slug']] = $values[U::ORGANISATION_NAME['slug']];
        $postedVals[U::AUTHORISED_PERSON_ID['slug']] = $values[U::AUTHORISED_PERSON_ID['slug']];
        $postedVals[U::AUTHORISED_PERSON_NAME['slug']] = $values[U::AUTHORISED_PERSON_NAME['slug']];
        $postedVals[U::AUTHORISED_PERSON_DESIGNATION['slug']] = $values[U::AUTHORISED_PERSON_DESIGNATION['slug']];
        $postedVals[U::AUTHORISED_PERSON_PHONE['slug']] = $values[U::AUTHORISED_PERSON_PHONE['slug']];
        $postedVals[U::AUTHORISED_PERSON_EMAIL['slug']] = $values[U::AUTHORISED_PERSON_EMAIL['slug']];
        $postedVals[U::REPORT_URL['slug']] = $values[U::REPORT_URL['slug']];
        $postedVals[U::CALLBACK_URL['slug']] = $values[U::CALLBACK_URL['slug']];
        $postedVals[U::VALIDATE_ONLY['slug']] = $values[U::VALIDATE_ONLY['slug']];
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

        if (U::parsUENNumber($postedVals[U::ORGANISATION_ID['slug']]) == 0) {
            CRM_Core_Session::setStatus('Incorrect organization ID(UEN)', ts('Incorrect UEN'), 'warning', array('expires' => 5000));
            return;
        }
        //if all is ok clear parametrs

        $s = CRM_Core_BAO_Setting::setItem($postedVals, U::SETTINGS_NAME, U::SETTINGS_SLUG);
//        U::writeLog($s);

        CRM_Core_Session::setStatus('Configuration saved successfully', ts('Success'), 'success', array('expires' => 5000));

        parent::postProcess();
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
