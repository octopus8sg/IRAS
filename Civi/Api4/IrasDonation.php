<?php

namespace Civi\Api4;

__autoload();
// if(!@include_once(dirname(__FILE__) . './IrasOnlineReport.php')) {
//   //Logic here
// }
//   require_once(dirname(__FILE__) . './IrasOnlineReport.php');
// }catch(Exception $e){};

use CRM_IrasOnlineReport;
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
      $cl = new CRM_IrasOnlineReport();
      return [
        [
          'state' => $cl->onlineReport($getFieldsAction),
          'code' => 200
        ]
      ];
    }))->setCheckPermissions($checkPermissions);
  }
}

function __autoload()
{
  require_once(dirname(__FILE__) . '\..\..\Generic\Report.php');
  require_once(dirname(__FILE__) . '\IrasOnlineReport.php');
}