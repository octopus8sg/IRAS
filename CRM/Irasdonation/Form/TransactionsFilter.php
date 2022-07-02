<?php

use CRM_Irasdonation_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Irasdonation_Form_TransactionsFilter extends CRM_Core_Form
{
  public function buildQuickForm()
  {
    // add form elements
    $this->add(
      'select', // field type
      'is_api', // field name
      'Sent Method', // field label
      [2=>'Not sent', 0 => 'Offline', 1 => 'API'], // list of options
      FALSE // is required
    );

    // add form elements
    $this->add(
      'select', // field type
      'sent_response', // field name
      'Sent Response', // field label
      [10 => 'Success', 30 => 'Fail'], // list of options
      FALSE // is required
    );

    //start report from 
    $this->add('datepicker', 'trn_start_date', ts('Transaction From'), [], FALSE, ['time' => FALSE]);

    //end report to
    $this->add('datepicker', 'trn_end_date', ts('Transaction To'), [], FALSE, ['time' => FALSE]);

    //start report from 
    $this->add('datepicker', 'sent_start_date', ts('Sent From'), [], FALSE, ['time' => FALSE]);

    //end report to
    $this->add('datepicker', 'sent_end_date', ts('Sent To'), [], FALSE, ['time' => FALSE]);

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Generate and download report'),
        'isDefault' => TRUE,
      ),
    ));

    // export form elements
    //$this->assign('elementNames', $this->getRenderableElementNames());
    $this->assign('suppressForm', FALSE);
    parent::buildQuickForm();
  }

  public function postProcess()
  {
    $values = $this->exportValues();
    $options = $this->getColorOptions();
    CRM_Core_Session::setStatus(E::ts('You picked color "%1"', array(
      1 => $options[$values['favorite_color']],
    )));
    parent::postProcess();
  }

  public function getColorOptions()
  {
    $options = array(
      '' => E::ts('- select -'),
      '#f00' => E::ts('Red'),
      '#0f0' => E::ts('Green'),
      '#00f' => E::ts('Blue'),
      '#f0f' => E::ts('Purple'),
    );
    foreach (array('1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e') as $f) {
      $options["#{$f}{$f}{$f}"] = E::ts('Grey (%1)', array(1 => $f));
    }
    return $options;
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
