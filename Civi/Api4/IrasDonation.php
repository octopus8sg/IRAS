<?php

namespace Civi\Api4;
require_once(dirname(__FILE__) . '/../../Generic/Report.php');
require_once(dirname(__FILE__) . './IrasOnlineReport.php');
use CRM_Irasdonation_Form_IrasOnlineReport;
/**
 * IrasDonation entity.
 *
 * Provided by the IRAS Donation extension.
 *
 * @package Civi\Api4
 */
class IrasDonation extends Generic\DAOEntity
{
  public static function report($checkPermissions = TRUE)
  {
    return (new Generic\Report(__CLASS__, __FUNCTION__, function ($getFieldsAction) {
      $cl = new CRM_Irasdonation_Form_IrasOnlineReport();
      return [
        [
          'state' => $cl->onlineReport(),
          'code' => 200
        ]
      ];
    }))->setCheckPermissions($checkPermissions);
  }
}
