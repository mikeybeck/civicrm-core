<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from xml/schema/CRM/Core/Navigation.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:3629ca1a8d13a6a1311e887aecbece7d)
 */

/**
 * Database access object for the Navigation entity.
 */
class CRM_Core_DAO_Navigation extends CRM_Core_DAO {
  const EXT = 'civicrm';
  const TABLE_ADDED = '3.0';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_navigation';

  /**
   * Field to show when displaying a record.
   *
   * @var string
   */
  public static $_labelField = 'label';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = FALSE;

  /**
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Which Domain is this navigation item for
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $domain_id;

  /**
   * Navigation Title
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $label;

  /**
   * Internal Name
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $name;

  /**
   * url in case of custom navigation link
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $url;

  /**
   * CSS class name for an icon
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $icon;

  /**
   * Permission(s) needed to access menu item
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $permission;

  /**
   * Operator to use if item has more than one permission
   *
   * @var string|null
   *   (SQL type: varchar(3))
   *   Note that values will be retrieved from the database as a string.
   */
  public $permission_operator;

  /**
   * Parent navigation item, used for grouping
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $parent_id;

  /**
   * Is this navigation item active?
   *
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_active;

  /**
   * Place a separator either before or after this menu item.
   *
   * @var int|string|null
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $has_separator;

  /**
   * Ordering of the navigation items in various blocks.
   *
   * @var int|string
   *   (SQL type: int)
   *   Note that values will be retrieved from the database as a string.
   */
  public $weight;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_navigation';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? ts('Navigations') : ts('Navigation');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'domain_id', 'civicrm_domain', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'parent_id', 'civicrm_navigation', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
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
          'title' => ts('Navigation ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.id',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => '3.0',
        ],
        'domain_id' => [
          'name' => 'domain_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Domain ID'),
          'description' => ts('Which Domain is this navigation item for'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.domain_id',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'FKClassName' => 'CRM_Core_DAO_Domain',
          'html' => [
            'label' => ts("Domain"),
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_domain',
            'keyColumn' => 'id',
            'labelColumn' => 'name',
          ],
          'add' => '3.0',
        ],
        'label' => [
          'name' => 'label',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Navigation Item Label'),
          'description' => ts('Navigation Title'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.label',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'add' => '3.0',
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Navigation Item Machine Name'),
          'description' => ts('Internal Name'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.name',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'add' => '3.0',
        ],
        'url' => [
          'name' => 'url',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Url'),
          'description' => ts('url in case of custom navigation link'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.url',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'add' => '3.0',
        ],
        'icon' => [
          'name' => 'icon',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Icon'),
          'description' => ts('CSS class name for an icon'),
          'required' => FALSE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.icon',
          'default' => NULL,
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'add' => '4.7',
        ],
        'permission' => [
          'name' => 'permission',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Required Permission'),
          'description' => ts('Permission(s) needed to access menu item'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.permission',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'serialize' => self::SERIALIZE_COMMA,
          'add' => '3.0',
        ],
        'permission_operator' => [
          'name' => 'permission_operator',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Permission Operator'),
          'description' => ts('Operator to use if item has more than one permission'),
          'maxlength' => 3,
          'size' => CRM_Utils_Type::FOUR,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.permission_operator',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'pseudoconstant' => [
            'callback' => 'CRM_Core_SelectValues::andOr',
          ],
          'add' => '3.0',
        ],
        'parent_id' => [
          'name' => 'parent_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('parent ID'),
          'description' => ts('Parent navigation item, used for grouping'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.parent_id',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'FKClassName' => 'CRM_Core_DAO_Navigation',
          'html' => [
            'label' => ts("parent"),
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_navigation',
            'keyColumn' => 'id',
            'labelColumn' => 'label',
            'nameColumn' => 'name',
          ],
          'add' => '3.0',
        ],
        'is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Is Active'),
          'description' => ts('Is this navigation item active?'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.is_active',
          'default' => '1',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
            'label' => ts("Enabled"),
          ],
          'add' => '3.0',
        ],
        'has_separator' => [
          'name' => 'has_separator',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Separator'),
          'description' => ts('Place a separator either before or after this menu item.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.has_separator',
          'default' => '0',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'pseudoconstant' => [
            'callback' => 'CRM_Core_SelectValues::navigationMenuSeparator',
          ],
          'add' => '3.0',
        ],
        'weight' => [
          'name' => 'weight',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Order'),
          'description' => ts('Ordering of the navigation items in various blocks.'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_navigation.weight',
          'default' => '0',
          'table_name' => 'civicrm_navigation',
          'entity' => 'Navigation',
          'bao' => 'CRM_Core_BAO_Navigation',
          'localizable' => 0,
          'add' => '3.0',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'navigation', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'navigation', $prefix, []);
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
