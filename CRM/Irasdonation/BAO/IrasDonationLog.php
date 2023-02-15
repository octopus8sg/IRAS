<?php
use CRM_Irasdonation_ExtensionUtil as E;

class CRM_Irasdonation_BAO_IrasDonationLog extends CRM_Irasdonation_DAO_IrasDonationLog {

  /**
   * Create a new IrasDonationLog based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Irasdonation_DAO_IrasDonationLog|NULL
   *
  public static function create($params) {
    $className = 'CRM_Irasdonation_DAO_IrasDonationLog';
    $entityName = 'IrasDonationLog';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
