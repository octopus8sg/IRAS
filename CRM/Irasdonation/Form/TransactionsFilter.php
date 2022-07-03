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
      'method', // field name
      'Sent Method: ', // field label
      [null => 'All', 1 => 'New transactions', 2 => 'Offline', 3 => 'API'], // list of options
      FALSE // is required
    );

    // add form elements
    $this->add(
      'select', // field type
      'sent_response', // field name
      'Sent Response: ', // field label
      [null => 'All', 10 => 'Success', 30 => 'Fail'], // list of options
      FALSE // is required
    );

    $this->addDatePickerRange(
      'transaction_range',
      'Select Date',
      FALSE,
      NULL,
      'Transaction From: ',
      'Transaction To: ',
      [],
      '_end_date',
      '_start_date'
    );

    $this->addDatePickerRange(
      'sent_range',
      'Select Date',
      FALSE,
      NULL,
      'Sent From: ',
      'Sent To: ',
      [],
      '_end_date',
      '_start_date'
    );

    // export form elements
    $this->assign('suppressForm', FALSE);
    parent::buildQuickForm();
  }
}
