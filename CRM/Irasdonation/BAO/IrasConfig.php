<?php
use CRM_Irasdonation_ExtensionUtil as E;

class CRM_Irasdonation_BAO_IrasConfig extends CRM_Irasdonation_DAO_IrasConfig {

  /**
   * Create a new IrasConfig based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Irasdonation_DAO_IrasConfig|NULL
   *
  public static function create($params) {
    $className = 'CRM_Irasdonation_DAO_IrasConfig';
    $entityName = 'IrasConfig';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
