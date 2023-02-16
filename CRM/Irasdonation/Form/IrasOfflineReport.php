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
        $lastDayOfYear = date('Y-12-31');
        $startDate = CRM_Utils_Array::value('start_date', $values, null);
        $endDate = CRM_Utils_Array::value('end_date', $values, null);
        $includePrevious = $values["include_previous"];
        $reportYear = date("Y");
        if (!$startDate) {
            $startDate = $firstDayOfYear;
        };
        if (!$endDate) {
            $endDate = $lastDayOfYear;
        };
        if ($startDate != null) {
            $reportYear = date("Y", strtotime($startDate));
        };
//        U::writeLog($startDate, "startDate");
//        U::writeLog($endDate, "endDate");
//        U::writeLog(strval($includePrevious), "includePrevious");
        if (date("Y", strtotime($endDate)) != date("Y", strtotime($startDate))) {
            CRM_Core_Session::setStatus('Selected Date must be in the same year', ts('Date range incorrect'), 'warning', array('expires' => 5000));
            return;
        }

        //get donations

        list($totalRows, $total, $counter, $generatedDate, $donations, $online_donations, $offline_donations) = U::prepareDonations($startDate, $endDate, $includePrevious);
        if ($totalRows > 5000) {
            CRM_Core_Session::setStatus('You have more than 5000 records, please select smaller period of time', ts('Date range incorrect'), 'warning', array('expires' => 5000));
        }
        //prepare header
        //return 0;
        if ($counter > 0) {
            $offline_report_csv = CRM_Irasdonation_Utils::prepareOfflineReport($reportYear, $organisation_id, $total, $counter, $offline_donations);
            list($online_report_body,
                $validate_only,
                $basis_year,
                $organisation_id_type,
                $organisation_id_no,
                $organisation_name,
                $batch_indicator,
                $authorised_person_name,
                $authorised_person_designation,
                $telephone,
                $authorised_person_email,
                $num_of_records,
                $total_donation_amount) = U::prepareOnlineReportBody($reportYear, $counter, $total, $online_donations);

            $response_body = U::generateCsv($offline_report_csv);
            CRM_Core_Session::setStatus('File generated successfully', ts('File Generation'), 'success', array('expires' => 10000));
            $response_code = 10;
            $is_api = 0;
            $validate_only = intval($validate_only);
            U::saveDonationLogs($is_api,
                $validate_only,
                $basis_year,
                $organisation_id_type,
                $organisation_id_no,
                $organisation_name,
                $batch_indicator,
                $authorised_person_name,
                $authorised_person_designation,
                $telephone,
                $authorised_person_email,
                $num_of_records,
                $total_donation_amount,
                $response_body,
                $response_code,
                $generatedDate,
                $donations);
            exit();
        } else CRM_Core_Session::setStatus('No data to generate report', ts('All reports are generated'), 'success', array('expires' => 10000));

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
