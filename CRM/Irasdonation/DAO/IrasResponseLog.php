<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.iras/xml/schema/CRM/Irasdonation/01IrasResponseLog.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:9da0833c4798c0e0186d78d46d7cbdde)
 */
use CRM_Irasdonation_ExtensionUtil as E;

/**
 * Database access object for the IrasResponseLog entity.
 */
class CRM_Irasdonation_DAO_IrasResponseLog extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_iras_response_log';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * api or offline report
   *
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_api;

  /**
   * validateOnly
   *
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $validate_only;

  /**
   * BasisYear
   *
   * @var string
   *   (SQL type: varchar(4))
   *   Note that values will be retrieved from the database as a string.
   */
  public $basis_year;

  /**
   * organisationIDType
   *
   * @var string
   *   (SQL type: varchar(60))
   *   Note that values will be retrieved from the database as a string.
   */
  public $organisation_id_type;

  /**
   * organisationIDNo
   *
   * @var string
   *   (SQL type: varchar(60))
   *   Note that values will be retrieved from the database as a string.
   */
  public $organisation_id_no;

  /**
   * organisationName
   *
   * @var string
   *   (SQL type: varchar(60))
   *   Note that values will be retrieved from the database as a string.
   */
  public $organisation_name;

  /**
   * batchIndicator
   *
   * @var string
   *   (SQL type: varchar(4))
   *   Note that values will be retrieved from the database as a string.
   */
  public $batch_indicator;

  /**
   * authorisedPersonIDNo
   *
   * @var string
   *   (SQL type: varchar(60))
   *   Note that values will be retrieved from the database as a string.
   */
  public $authorised_person_id_no;

  /**
   * authorisedPersonName
   *
   * @var string
   *   (SQL type: varchar(60))
   *   Note that values will be retrieved from the database as a string.
   */
  public $authorised_person_name;

  /**
   * authorisedPersonDesignation
   *
   * @var string
   *   (SQL type: varchar(60))
   *   Note that values will be retrieved from the database as a string.
   */
  public $authorised_person_designation;

  /**
   * telephone
   *
   * @var string
   *   (SQL type: varchar(60))
   *   Note that values will be retrieved from the database as a string.
   */
  public $telephone;

  /**
   * authorisedPersonEmail
   *
   * @var string
   *   (SQL type: varchar(60))
   *   Note that values will be retrieved from the database as a string.
   */
  public $authorised_person_email;

  /**
   * numOfRecords
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $num_of_records;

  /**
   * totalDonationAmount
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $total_donation_amount;

  /**
   * json response of request
   *
   * @var string
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $response_body;

  /**
   * response code
   *
   * @var int|string
   *   (SQL type: int)
   *   Note that values will be retrieved from the database as a string.
   */
  public $response_code;

  /**
   * When the response was first received
   *
   * @var string|null
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_date;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_o8_iras_response_log';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Iras Response Logs') : E::ts('Iras Response Log');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('Unique ID'),
          'required' => TRUE,
          'where' => 'civicrm_o8_iras_response_log.id',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'is_api' => [
          'name' => 'is_api',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'description' => E::ts('api or offline report'),
          'required' => FALSE,
          'where' => 'civicrm_o8_iras_response_log.is_api',
          'default' => 'false',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'validate_only' => [
          'name' => 'validate_only',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Validate Only'),
          'description' => E::ts('validateOnly'),
          'required' => FALSE,
          'where' => 'civicrm_o8_iras_response_log.validate_only',
          'default' => 'true',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'basis_year' => [
          'name' => 'basis_year',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Basis Year'),
          'description' => E::ts('BasisYear'),
          'required' => FALSE,
          'maxlength' => 4,
          'size' => CRM_Utils_Type::FOUR,
          'where' => 'civicrm_o8_iras_response_log.basis_year',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'organisation_id_type' => [
          'name' => 'organisation_id_type',
          'type' => CRM_Utils_Type::T_STRING,
          'description' => E::ts('organisationIDType'),
          'required' => FALSE,
          'maxlength' => 60,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_o8_iras_response_log.organisation_id_type',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'organisation_id_no' => [
          'name' => 'organisation_id_no',
          'type' => CRM_Utils_Type::T_STRING,
          'description' => E::ts('organisationIDNo'),
          'required' => FALSE,
          'maxlength' => 60,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_o8_iras_response_log.organisation_id_no',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'organisation_name' => [
          'name' => 'organisation_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Organisation Name'),
          'description' => E::ts('organisationName'),
          'required' => FALSE,
          'maxlength' => 60,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_o8_iras_response_log.organisation_name',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'batch_indicator' => [
          'name' => 'batch_indicator',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Batch Indicator'),
          'description' => E::ts('batchIndicator'),
          'required' => FALSE,
          'maxlength' => 4,
          'size' => CRM_Utils_Type::FOUR,
          'where' => 'civicrm_o8_iras_response_log.batch_indicator',
          'default' => '0',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'authorised_person_id_no' => [
          'name' => 'authorised_person_id_no',
          'type' => CRM_Utils_Type::T_STRING,
          'description' => E::ts('authorisedPersonIDNo'),
          'required' => FALSE,
          'maxlength' => 60,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_o8_iras_response_log.authorised_person_id_no',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'authorised_person_name' => [
          'name' => 'authorised_person_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Authorised Person Name'),
          'description' => E::ts('authorisedPersonName'),
          'required' => FALSE,
          'maxlength' => 60,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_o8_iras_response_log.authorised_person_name',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'authorised_person_designation' => [
          'name' => 'authorised_person_designation',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Authorised Person Designation'),
          'description' => E::ts('authorisedPersonDesignation'),
          'required' => FALSE,
          'maxlength' => 60,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_o8_iras_response_log.authorised_person_designation',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'telephone' => [
          'name' => 'telephone',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Telephone'),
          'description' => E::ts('telephone'),
          'required' => FALSE,
          'maxlength' => 60,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_o8_iras_response_log.telephone',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'authorised_person_email' => [
          'name' => 'authorised_person_email',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Authorised Person Email'),
          'description' => E::ts('authorisedPersonEmail'),
          'required' => FALSE,
          'maxlength' => 60,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_o8_iras_response_log.authorised_person_email',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'num_of_records' => [
          'name' => 'num_of_records',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Num Of Records'),
          'description' => E::ts('numOfRecords'),
          'required' => FALSE,
          'where' => 'civicrm_o8_iras_response_log.num_of_records',
          'default' => '0',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'total_donation_amount' => [
          'name' => 'total_donation_amount',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Total Donation Amount'),
          'description' => E::ts('totalDonationAmount'),
          'required' => FALSE,
          'where' => 'civicrm_o8_iras_response_log.total_donation_amount',
          'default' => '0',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'response_body' => [
          'name' => 'response_body',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Response Body'),
          'description' => E::ts('json response of request'),
          'required' => FALSE,
          'where' => 'civicrm_o8_iras_response_log.response_body',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'html' => [
            'type' => 'TextArea',
          ],
          'add' => NULL,
        ],
        'response_code' => [
          'name' => 'response_code',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Response Code'),
          'description' => E::ts('response code'),
          'required' => FALSE,
          'where' => 'civicrm_o8_iras_response_log.response_code',
          'default' => NULL,
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'created_date' => [
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Created Date'),
          'description' => E::ts('When the response was first received'),
          'where' => 'civicrm_o8_iras_response_log.created_date',
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_o8_iras_response_log',
          'entity' => 'IrasResponseLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'localizable' => 0,
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_iras_response_log', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_iras_response_log', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'index_created_date' => [
        'name' => 'index_created_date',
        'field' => [
          0 => 'created_date',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_o8_iras_response_log::0::created_date',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
