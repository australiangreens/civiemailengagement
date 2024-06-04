<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from civiemailengagement/xml/schema/CRM/CiviEmailEngagement/ContactEmailEngagement.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:cb86d02b0a830bec3d10b0fa4c1cc7a2)
 */
use CRM_CiviEmailEngagement_ExtensionUtil as E;

/**
 * Database access object for the ContactEmailEngagement entity.
 */
class CRM_CiviEmailEngagement_DAO_ContactEmailEngagement extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_contact_email_engagement';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique ContactEmailEngagement ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * FK to Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $contact_id;

  /**
   * Date of last relevant email clickthrough
   *
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $date_last_click;

  /**
   * Date of first relevant email clickthrough
   *
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $date_first_click;

  /**
   * Date of last calculation of email engagement values
   *
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $date_calculated;

  /**
   * Number of clickthroughs in reporting period
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $volume;

  /**
   * Number of clickthroughs in last 30 days
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $volume_last_30_days;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_contact_email_engagement';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Contact Email Engagements') : E::ts('Contact Email Engagement');
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
          'title' => E::ts('ID'),
          'description' => E::ts('Unique ContactEmailEngagement ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_contact_email_engagement.id',
          'table_name' => 'civicrm_contact_email_engagement',
          'entity' => 'ContactEmailEngagement',
          'bao' => 'CRM_CiviEmailEngagement_DAO_ContactEmailEngagement',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'contact_id' => [
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Contact ID'),
          'description' => E::ts('FK to Contact'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_contact_email_engagement.contact_id',
          'table_name' => 'civicrm_contact_email_engagement',
          'entity' => 'ContactEmailEngagement',
          'bao' => 'CRM_CiviEmailEngagement_DAO_ContactEmailEngagement',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'add' => NULL,
        ],
        'date_last_click' => [
          'name' => 'date_last_click',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Date Last Click'),
          'description' => E::ts('Date of last relevant email clickthrough'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_contact_email_engagement.date_last_click',
          'table_name' => 'civicrm_contact_email_engagement',
          'entity' => 'ContactEmailEngagement',
          'bao' => 'CRM_CiviEmailEngagement_DAO_ContactEmailEngagement',
          'localizable' => 0,
          'add' => NULL,
        ],
        'date_first_click' => [
          'name' => 'date_first_click',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Date First Click'),
          'description' => E::ts('Date of first relevant email clickthrough'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_contact_email_engagement.date_first_click',
          'table_name' => 'civicrm_contact_email_engagement',
          'entity' => 'ContactEmailEngagement',
          'bao' => 'CRM_CiviEmailEngagement_DAO_ContactEmailEngagement',
          'localizable' => 0,
          'add' => NULL,
        ],
        'date_calculated' => [
          'name' => 'date_calculated',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Date Calculated'),
          'description' => E::ts('Date of last calculation of email engagement values'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_contact_email_engagement.date_calculated',
          'table_name' => 'civicrm_contact_email_engagement',
          'entity' => 'ContactEmailEngagement',
          'bao' => 'CRM_CiviEmailEngagement_DAO_ContactEmailEngagement',
          'localizable' => 0,
          'add' => NULL,
        ],
        'volume' => [
          'name' => 'volume',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Volume'),
          'description' => E::ts('Number of clickthroughs in reporting period'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_contact_email_engagement.volume',
          'table_name' => 'civicrm_contact_email_engagement',
          'entity' => 'ContactEmailEngagement',
          'bao' => 'CRM_CiviEmailEngagement_DAO_ContactEmailEngagement',
          'localizable' => 0,
          'add' => NULL,
        ],
        'volume_last_30_days' => [
          'name' => 'volume_last_30_days',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Volume Last 30 Days'),
          'description' => E::ts('Number of clickthroughs in last 30 days'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_contact_email_engagement.volume_last_30_days',
          'table_name' => 'civicrm_contact_email_engagement',
          'entity' => 'ContactEmailEngagement',
          'bao' => 'CRM_CiviEmailEngagement_DAO_ContactEmailEngagement',
          'localizable' => 0,
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'contact_email_engagement', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'contact_email_engagement', $prefix, []);
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
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
