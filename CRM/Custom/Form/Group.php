<?php
/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */

/**
 * form to process actions on the set aspect of Custom Data
 */
class CRM_Custom_Form_Group extends CRM_Core_Form {

  /**
   * The set id saved to the session for an update.
   *
   * @var int
   */
  public $_id;

  /**
   *  set is empty or not.
   *
   * @var bool
   */
  protected $_isGroupEmpty = TRUE;

  /**
   * Array of existing subtypes set for a custom set.
   *
   * @var array
   */
  protected $_subtypes = [];

  /**
   * Set variables up before form is built.
   *
   *
   * @return void
   */
  public function preProcess() {
    $this->preventAjaxSubmit();
    Civi::resources()->addScriptFile('civicrm', 'js/jquery/jquery.crmIconPicker.js');

    // current set id
    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this);
    $this->setAction($this->_id ? CRM_Core_Action::UPDATE : CRM_Core_Action::ADD);

    if ($this->_id && CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomGroup', $this->_id, 'is_reserved', 'id')) {
      CRM_Core_Error::statusBounce("You cannot edit the settings of a reserved custom field-set.");
    }

    if ($this->_id) {
      $title = CRM_Core_BAO_CustomGroup::getTitle($this->_id);
      $this->setTitle(ts('Edit %1', [1 => $title]));
      $params = ['id' => $this->_id];
      CRM_Core_BAO_CustomGroup::retrieve($params, $this->_defaults);

      $subExtends = $this->_defaults['extends_entity_column_value'] ?? NULL;
      if (!empty($subExtends)) {
        $this->_subtypes = explode(CRM_Core_DAO::VALUE_SEPARATOR, substr($subExtends, 1, -1));
      }
    }
    else {
      $this->setTitle(ts('New Custom Field Set'));
    }
  }

  /**
   * Global form rule.
   *
   * @param array $fields
   *   The input form values.
   * @param array $files
   *   The uploaded files if any.
   * @param self $self
   *
   *
   * @return bool|array
   *   true if no errors, else array of errors
   */
  public static function formRule($fields, $files, $self) {
    $errors = [];

    //validate group title as well as name.
    $title = $fields['title'];
    $name = CRM_Utils_String::munge($title, '_', 64);
    $query = 'select count(*) from civicrm_custom_group where ( name like %1) and id != %2';
    $grpCnt = CRM_Core_DAO::singleValueQuery($query, [
      1 => [$name, 'String'],
      2 => [(int) $self->_id, 'Integer'],
    ]);
    if ($grpCnt) {
      $errors['title'] = ts('Custom group \'%1\' already exists in Database.', [1 => $title]);
    }

    if (!empty($fields['extends'][1])) {
      if (in_array('', $fields['extends'][1]) && count($fields['extends'][1]) > 1) {
        $errors['extends'] = ts("Cannot combine other option with 'Any'.");
      }
    }

    if (empty($fields['extends'][0])) {
      $errors['extends'] = ts("You need to select the type of record that this set of custom fields is applicable for.");
    }

    $extends = ['Activity', 'Relationship', 'Group', 'Contribution', 'Membership', 'Event', 'Participant'];
    if (in_array($fields['extends'][0], $extends) && $fields['style'] == 'Tab') {
      $errors['style'] = ts("Display Style should be Inline for this Class");
      $self->assign('showStyle', TRUE);
    }

    if (!empty($fields['is_multiple'])) {
      $self->assign('showMultiple', TRUE);
    }

    if (empty($fields['is_multiple']) && $fields['style'] == 'Tab with table') {
      $errors['style'] = ts("Display Style 'Tab with table' is only supported for multiple-record custom field sets.");
    }

    //checks the given custom set doesnot start with digit
    $title = $fields['title'];
    if (!empty($title)) {
      // gives the ascii value
      $asciiValue = ord($title[0]);
      if ($asciiValue >= 48 && $asciiValue <= 57) {
        $errors['title'] = ts("Name cannot not start with a digit");
      }
    }

    return empty($errors) ? TRUE : $errors;
  }

  /**
   * add the rules (mainly global rules) for form.
   * All local rules are added near the element
   *
   *
   * @return void
   * @see valid_date
   */
  public function addRules() {
    $this->addFormRule(['CRM_Custom_Form_Group', 'formRule'], $this);
  }

  /**
   * Build the form object.
   *
   *
   * @return void
   */
  public function buildQuickForm() {
    $this->applyFilter('__ALL__', 'trim');

    $attributes = CRM_Core_DAO::getAttribute('CRM_Core_DAO_CustomGroup');

    //title
    $this->add('text', 'title', ts('Set Name'), $attributes['title'], TRUE);

    //Fix for code alignment, CRM-3058
    $contactTypes = array_merge(['Contact'], CRM_Contact_BAO_ContactType::basicTypes());
    $this->assign('contactTypes', json_encode($contactTypes));

    $sel1 = ["" => ts("- select -")] + CRM_Core_SelectValues::customGroupExtends();
    ksort($sel1);
    $sel2 = CRM_Core_BAO_CustomGroup::getSubTypes();

    foreach ($sel2 as $main => $sub) {
      if (!empty($sel2[$main])) {
        $sel2[$main] = [
          '' => ts("- Any -"),
        ] + $sel2[$main];
      }
    }

    $sel = &$this->add('hierselect',
      'extends',
      ts('Used For'),
      [
        'name' => 'extends[0]',
        'style' => 'vertical-align: top;',
      ],
      TRUE
    );
    $sel->setOptions([$sel1, $sel2]);
    if (is_a($sel->_elements[1], 'HTML_QuickForm_select')) {
      // make second selector a multi-select -
      $sel->_elements[1]->setMultiple(TRUE);
      $sel->_elements[1]->setSize(5);
    }
    if ($this->_action == CRM_Core_Action::UPDATE) {
      $subName = $this->_defaults['extends_entity_column_id'] ?? NULL;
      if ($this->_defaults['extends'] == 'Participant') {
        if ($subName == 1) {
          $this->_defaults['extends'] = 'ParticipantRole';
        }
        elseif ($subName == 2) {
          $this->_defaults['extends'] = 'ParticipantEventName';
        }
        elseif ($subName == 3) {
          $this->_defaults['extends'] = 'ParticipantEventType';
        }
      }

      //allow to edit settings if custom set is empty CRM-5258
      $this->_isGroupEmpty = CRM_Core_BAO_CustomGroup::isGroupEmpty($this->_id);
      if (!$this->_isGroupEmpty) {
        if (!empty($this->_subtypes)) {
          // we want to allow adding / updating subtypes for this case,
          // and therefore freeze the first selector only.
          $sel->_elements[0]->freeze();
        }
        else {
          // freeze both the selectors
          $sel->freeze();
        }
      }
      $this->assign('isCustomGroupEmpty', $this->_isGroupEmpty);
      $this->assign('gid', $this->_id);
    }
    $this->assign('defaultSubtypes', json_encode($this->_subtypes));

    // help text
    $this->add('wysiwyg', 'help_pre', ts('Pre-form Help'), $attributes['help_pre']);
    $this->add('wysiwyg', 'help_post', ts('Post-form Help'), $attributes['help_post']);

    // weight
    $this->add('number', 'weight', ts('Order'), $attributes['weight'], TRUE);
    $this->addRule('weight', ts('is a numeric field'), 'numeric');

    // display style
    $this->add('select', 'style', ts('Display Style'), CRM_Core_SelectValues::customGroupStyle());

    $this->add('text', 'icon', ts('Tab icon'), ['class' => 'crm-icon-picker', 'allowClear' => TRUE]);

    // is this set collapsed or expanded ?
    $this->addElement('advcheckbox', 'collapse_display', ts('Collapse this set on initial display'));

    // is this set collapsed or expanded ? in advanced search
    $this->addElement('advcheckbox', 'collapse_adv_display', ts('Collapse this set in Advanced Search'));

    // is this set active ?
    $this->addElement('advcheckbox', 'is_active', ts('Is this Custom Data Set active?'));

    //Is this set visible on public pages?
    $this->addElement('advcheckbox', 'is_public', ts('Is this Custom Data Set public?'));

    // does this set have multiple record?
    $multiple = $this->addElement('advcheckbox', 'is_multiple',
      ts('Does this Custom Field Set allow multiple records?'), NULL);

    // $min_multiple = $this->add('text', 'min_multiple', ts('Minimum number of multiple records'), $attributes['min_multiple'] );
    // $this->addRule('min_multiple', ts('is a numeric field') , 'numeric');

    $max_multiple = $this->add('number', 'max_multiple', ts('Maximum number of multiple records'), $attributes['max_multiple']);
    $this->addRule('max_multiple', ts('is a numeric field'), 'numeric');

    //allow to edit settings if custom set is empty CRM-5258
    $this->assign('isGroupEmpty', $this->_isGroupEmpty);
    if (!$this->_isGroupEmpty) {
      $multiple->freeze();
      //$min_multiple->freeze();
      $max_multiple->freeze();
    }

    $this->assign('showStyle', FALSE);
    $this->assign('showMultiple', FALSE);
    $buttons = [
      [
        'type' => 'next',
        'name' => ts('Save'),
        'spacing' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ],
    ];
    if (!$this->_isGroupEmpty && !empty($this->_subtypes)) {
      $buttons[0]['class'] = 'crm-warnDataLoss';
    }
    $this->addButtons($buttons);
  }

  /**
   * Set default values for the form. Note that in edit/view mode
   * the default values are retrieved from the database
   *
   *
   * @return array
   *   array of default values
   */
  public function setDefaultValues() {
    $defaults = &$this->_defaults;
    $this->assign('showMaxMultiple', TRUE);
    if ($this->_action == CRM_Core_Action::ADD) {
      $defaults['weight'] = CRM_Utils_Weight::getDefaultWeight('CRM_Core_DAO_CustomGroup');

      $defaults['is_multiple'] = $defaults['min_multiple'] = 0;
      $defaults['is_active'] = $defaults['is_public'] = $defaults['collapse_adv_display'] = 1;
      $defaults['style'] = 'Inline';
    }
    elseif (empty($defaults['max_multiple']) && !$this->_isGroupEmpty) {
      $this->assign('showMaxMultiple', FALSE);
    }

    if (($this->_action & CRM_Core_Action::UPDATE) && !empty($defaults['is_multiple'])) {
      $defaults['collapse_display'] = 0;
    }

    if (isset($defaults['extends'])) {
      $extends = $defaults['extends'];
      unset($defaults['extends']);

      $defaults['extends'][0] = $extends;

      if (!empty($this->_subtypes)) {
        $defaults['extends'][1] = $this->_subtypes;
      }
      else {
        $defaults['extends'][1] = [0 => ''];
      }

      if ($extends == 'Relationship' && !empty($this->_subtypes)) {
        $relationshipDefaults = [];
        foreach ($defaults['extends'][1] as $donCare => $rel_type_id) {
          $relationshipDefaults[] = $rel_type_id;
        }

        $defaults['extends'][1] = $relationshipDefaults;
      }
    }

    return $defaults;
  }

  /**
   * Process the form.
   *
   *
   * @return void
   */
  public function postProcess() {
    // get the submitted form values.
    $params = $this->controller->exportValues('Group');
    $params['overrideFKConstraint'] = 0;
    if ($this->_action & CRM_Core_Action::UPDATE) {
      $params['id'] = $this->_id;
      if ($this->_defaults['extends'][0] != $params['extends'][0]) {
        $params['overrideFKConstraint'] = 1;
      }

      if (!empty($this->_subtypes)) {
        $subtypesToBeRemoved = [];
        $subtypesToPreserve = $params['extends'][1];
        // Don't remove any value if group is extended to -any- subtype
        if (!empty($subtypesToPreserve[0])) {
          $subtypesToBeRemoved = array_diff($this->_subtypes, array_intersect($this->_subtypes, $subtypesToPreserve));
        }
        CRM_Contact_BAO_ContactType::deleteCustomRowsOfSubtype($this->_id, $subtypesToBeRemoved, $subtypesToPreserve);
      }
    }
    elseif ($this->_action & CRM_Core_Action::ADD) {
      //new custom set , so lets set the created_id
      $session = CRM_Core_Session::singleton();
      $params['created_id'] = $session->get('userID');
      $params['created_date'] = date('YmdHis');
    }

    $result = civicrm_api3('CustomGroup', 'create', $params);
    $group = $result['values'][$result['id']];
    $this->_id = $result['id'];

    // reset the cache
    Civi::cache('fields')->flush();
    // reset ACL and system caches.
    CRM_Core_BAO_Cache::resetCaches();

    if ($this->_action & CRM_Core_Action::UPDATE) {
      CRM_Core_Session::setStatus(ts('Your custom field set \'%1 \' has been saved.', [1 => $group['title']]), ts('Saved'), 'success');
    }
    else {
      // Jump directly to adding a field if popups are disabled
      $action = CRM_Core_Resources::singleton()->ajaxPopupsEnabled ? '' : '/add';
      $url = CRM_Utils_System::url("civicrm/admin/custom/group/field$action", 'reset=1&new=1&gid=' . $group['id']);
      CRM_Core_Session::setStatus(ts("Your custom field set '%1' has been added. You can add custom fields now.",
        [1 => $group['title']]
      ), ts('Saved'), 'success');
      $session = CRM_Core_Session::singleton();
      $session->replaceUserContext($url);
    }

    // prompt Drupal Views users to update $db_prefix in settings.php, if necessary
    global $db_prefix;
    $config = CRM_Core_Config::singleton();
    if (is_array($db_prefix) && $config->userSystem->viewsExists()) {
      // get table_name for each custom group
      $tables = [];
      $sql = "SELECT table_name FROM civicrm_custom_group WHERE is_active = 1";
      $result = CRM_Core_DAO::executeQuery($sql);
      while ($result->fetch()) {
        $tables[$result->table_name] = $result->table_name;
      }

      // find out which tables are missing from the $db_prefix array
      $missingTableNames = array_diff_key($tables, $db_prefix);

      if (!empty($missingTableNames)) {
        CRM_Core_Session::setStatus(ts("To ensure that all of your custom data groups are available to Views, you may need to add the following key(s) to the db_prefix array in your settings.php file: '%1'.",
          [1 => implode(', ', $missingTableNames)]
        ), ts('Note'), 'info');
      }
    }
  }

  /**
   * Return a formatted list of relationship labels.
   *
   * @return array
   *   Array (int $id => string $label).
   */
  public static function getRelationshipTypes() {
    // Note: We include inactive reltypes because we don't want to break custom-data
    // UI when a reltype is disabled.
    return CRM_Core_DAO::executeQuery('
      SELECT
        id,
        (CASE 1
           WHEN label_a_b is not null AND label_b_a is not null AND label_a_b != label_b_a
            THEN concat(label_a_b, \' / \', label_b_a)
           WHEN label_a_b is not null
            THEN label_a_b
           WHEN label_b_a is not null
            THEN label_b_a
           ELSE concat("RelType #", id)
        END) as label
      FROM civicrm_relationship_type
      '
    )->fetchMap('id', 'label');
  }

}
