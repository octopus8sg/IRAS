<?php

namespace Civi\Api4;

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
      // $cl = new CRM_IrasOnlineReport();
      // $reponse = $cl->onlineReport($getFieldsAction);
      // return [
      //   [
      //     'state' => $reponse->info,
      //     'code' => $reponse->returnCode
      //   ]
      // ];
      return[
          [
              'state' => '',
              'code' => ''
          ]
        ];
    }))->setCheckPermissions($checkPermissions);
  }
}