<?php

use Civi\Api4\IrasDonation;
use CRM_Irasdonation_ExtensionUtil as E;
use CRM_Irasdonation_Utils as U;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Irasdonation_Form_IrasOfflineReport extends CRM_Core_Form
{

    public function buildQuickForm()
    {
        //start report from
        $this->add('datepicker', 'start_date', ts('Start date'), [], FALSE, ['time' => FALSE]);

        //end report to
        $this->add('datepicker', 'end_date', ts('End date'), [], FALSE, ['time' => FALSE]);

        //include previously generateed reports
        $this->add('advcheckbox', 'include_previous', ts('Include receipts previously generated'));

        $this->addButtons(array(
            array(
                'type' => 'submit',
                'name' => E::ts('Generate and download report'),
                'isDefault' => TRUE,
            ),
        ));

        // export form elements
        $this->assign('elementNames', $this->getRenderableElementNames());
        parent::buildQuickForm();
    }

    public function postProcess()
    {
        $settings = U::getSettings();
        $organisation_id = CRM_Utils_Array::value(U::ORGANISATION_ID['slug'], $settings);
        if (!$organisation_id) {
            CRM_Core_Session::setStatus('Please configure extension before using', ts('Extension configuration'), 'warning', array('expires' => 5000));
            return;
        }

        $values = $this->exportValues();
        $firstDayOfYear = date('Y-01-01');
        $lastDayOfYear = date('Y-12-t');
        $startDate = CRM_Utils_Array::value('start_date', $values, $firstDayOfYear);
        $endDate = CRM_Utils_Array::value('end_date', $values, $lastDayOfYear);
        $includePrevious = $values["include_previous"];
        $reportYear = date("Y");
        if ($startDate != null) {
            $reportYear = date("Y", strtotime($startDate));
        };

        if (date("Y", strtotime($endDate)) != date("Y", strtotime($startDate))) {
            CRM_Core_Session::setStatus('Selected Date must be in the same year', ts('Date range incorrect'), 'warning', array('expires' => 5000));
            return;
        }

        //generate header of report
        list($csvData, $genDate, $saveReport, $dataBody) = CRM_Irasdonation_Utils::prepareOfflineReportDetails($startDate, $endDate, $includePrevious);

        //return 0;
        if (count($saveReport) > 0) {
            $log_id = 0;

            $insert = "INSERT INTO civicrm_o8_iras_response_log(response_code, response_body, created_date) VALUES (10, '', '$genDate');";
            CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);
            $result = CRM_Core_DAO::executeQuery('SELECT LAST_INSERT_ID() id;', CRM_Core_DAO::$_nullArray);

            while ($result->fetch()) {
                $log_id = $result->id;
            }

            foreach ($saveReport as $value) {
                $insert = "INSERT INTO civicrm_o8_iras_donation(cdntaxreceipts_log_id, is_api, log_id, created_date) VALUES ($value, 0, $log_id, '$genDate');";
                CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);
            }
        }

        if (count($dataBody) > 0) {
            U::generateCsv($csvData);
            CRM_Core_Session::setStatus('File generated successfully', ts('File Generation'), 'success', array('expires' => 10000));
            exit();
        } else CRM_Core_Session::setStatus('No any data to generate report', ts('All reports are generated'), 'success', array('expires' => 10000));

        parent::postProcess();
    }

    /**
     * Get the fields/elements defined in this form.
     *
     * @return array (string)
     */
    public function getRenderableElementNames()
    {
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
