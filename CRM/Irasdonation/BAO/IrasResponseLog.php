<?php
use CRM_Irasdonation_ExtensionUtil as E;

class CRM_Irasdonation_BAO_IrasResponseLog extends CRM_Irasdonation_DAO_IrasResponseLog {

  /**
   * Create a new IrasResponseLog based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Irasdonation_DAO_IrasResponseLog|NULL
   *
  public static function create($params) {
    $className = 'CRM_Irasdonation_DAO_IrasResponseLog';
    $entityName = 'IrasResponseLog';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
