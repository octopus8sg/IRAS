<?php
use CRM_Irasdonation_ExtensionUtil as E;
use CRM_Irasdonation_Utils as U;

/**
 * Collection of upgrade steps.
 */
class CRM_Irasdonation_Upgrader extends CRM_Irasdonation_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Example: Run an external SQL script when the module is installed.
   *
  public function install() {
    $this->executeSqlFile('sql/myinstall.sql');
  }

  /**
   * Example: Work with entities usually not available during the install step.
   *
   * This method can be used for any post-install tasks. For example, if a step
   * of your installation depends on accessing an entity that is itself
   * created during the installation (e.g., a setting or a managed entity), do
   * so here to avoid order of operation problems.
   */
  // public function postInstall() {
  //  $customFieldId = civicrm_api3('CustomField', 'getvalue', array(
  //    'return' => array("id"),
  //    'name' => "customFieldCreatedViaManagedHook",
  //  ));
  //  civicrm_api3('Setting', 'create', array(
  //    'myWeirdFieldSetting' => array('id' => $customFieldId, 'weirdness' => 1),
  //  ));
  // }

  /**
   * Example: Run an external SQL script when the module is uninstalled.
   */
  // public function uninstall() {
  //  $this->executeSqlFile('sql/myuninstall.sql');
  // }

  /**
   * Example: Run a simple query when a module is enabled.
   */
   public function enable() {
//    CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
       $settings = [];
       $settings[U::SAVE_LOG['slug']] = 1;
       $settings[U::CLIENT_ID['slug']] = "193776c5-dcc9-410a-ad20-376f269ca68c";
       $settings[U::CLIENT_SECRET['slug']] = "sH8rR7tS8pY3dS2cX3dD7rP4vT3vB4rI4yP7dT0iO4jA2rP7dO";
       $settings[U::ORGANIZATION_TYPE['slug']] = "6";
       $settings[U::ORGANISATION_ID['slug']] = "180011032C";
       $settings[U::ORGANISATION_NAME['slug']] = "Octopus8_123456X";
       $settings[U::AUTHORISED_PERSON_ID['slug']] = "S3002879A";
       $settings[U::AUTHORISED_PERSON_NAME['slug']] = "Karl Trunbull";
       $settings[U::AUTHORISED_PERSON_DESIGNATION['slug']] = "Manager";
       $settings[U::AUTHORISED_PERSON_PHONE['slug']] = "6591478662";
       $settings[U::AUTHORISED_PERSON_EMAIL['slug']] = "karl@octopus8.com";
       $settings[U::IRAS_API_URL['slug']] = "https://apisandbox.iras.gov.sg/iras/sb";
       $settings[U::CALLBACK_URL['slug']] = "https://asliddin.socialservicesconnect.com/wp-json/iras/v1/report";
       $settings[U::VALIDATE_ONLY['slug']] = 1;
       $settings[U::MIN_AMOUNT['slug']] = 1;
       CRM_Core_BAO_Setting::setItem($settings, U::SETTINGS_NAME, U::SETTINGS_SLUG);
   }

  /**
   * Example: Run a simple query when a module is disabled.
   */
  // public function disable() {
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  // }

  /**
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   * @throws Exception
   */
  // public function upgrade_4200(): bool {
  //   $this->ctx->log->info('Applying update 4200');
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
  //   CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
  //   return TRUE;
  // }


  /**
   * Example: Run an external SQL script.
   *
   * @return TRUE on success
   * @throws Exception
   */
  // public function upgrade_4201(): bool {
  //   $this->ctx->log->info('Applying update 4201');
  //   // this path is relative to the extension base dir
  //   $this->executeSqlFile('sql/upgrade_4201.sql');
  //   return TRUE;
  // }


  /**
   * Example: Run a slow upgrade process by breaking it up into smaller chunk.
   *
   * @return TRUE on success
   * @throws Exception
   */
  // public function upgrade_4202(): bool {
  //   $this->ctx->log->info('Planning update 4202'); // PEAR Log interface

  //   $this->addTask(E::ts('Process first step'), 'processPart1', $arg1, $arg2);
  //   $this->addTask(E::ts('Process second step'), 'processPart2', $arg3, $arg4);
  //   $this->addTask(E::ts('Process second step'), 'processPart3', $arg5);
  //   return TRUE;
  // }
  // public function processPart1($arg1, $arg2) { sleep(10); return TRUE; }
  // public function processPart2($arg3, $arg4) { sleep(10); return TRUE; }
  // public function processPart3($arg5) { sleep(10); return TRUE; }

  /**
   * Example: Run an upgrade with a query that touches many (potentially
   * millions) of records by breaking it up into smaller chunks.
   *
   * @return TRUE on success
   * @throws Exception
   */
  // public function upgrade_4203(): bool {
  //   $this->ctx->log->info('Planning update 4203'); // PEAR Log interface

  //   $minId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(min(id),0) FROM cdntaxreceipts_log');
  //   $maxId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(max(id),0) FROM cdntaxreceipts_log');
  //   for ($startId = $minId; $startId <= $maxId; $startId += self::BATCH_SIZE) {
  //     $endId = $startId + self::BATCH_SIZE - 1;
  //     $title = E::ts('Upgrade Batch (%1 => %2)', array(
  //       1 => $startId,
  //       2 => $endId,
  //     ));
  //     $sql = '
  //       UPDATE cdntaxreceipts_log SET foobar = whiz(wonky()+wanker)
  //       WHERE id BETWEEN %1 and %2
  //     ';
  //     $params = array(
  //       1 => array($startId, 'Integer'),
  //       2 => array($endId, 'Integer'),
  //     );
  //     $this->addTask($title, 'executeSql', $sql, $params);
  //   }
  //   return TRUE;
  // }

}
