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
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */

/**
 * This class generates form components for processing Event.
 */
class CRM_Event_Form_ManageEvent_EventInfo extends CRM_Event_Form_ManageEvent {

  /**
   * Event type.
   * @var int
   */
  protected $_eventType = NULL;

  /**
   * Set variables up before form is built.
   */
  public function preProcess() {
    parent::preProcess();
    $this->setSelectedChild('settings');

    $entityID = $this->_id ?: $this->_templateId;
    if ($entityID) {
      $this->assign('entityID', $entityID);
      $eventType = CRM_Core_DAO::getFieldValue('CRM_Event_DAO_Event',
        $entityID,
        'event_type_id'
      );
    }
    else {
      $eventType = 'null';
    }

    $showLocation = FALSE;
    // when custom data is included in this page
    if (!empty($_POST['hidden_custom'])) {
      $this->set('type', 'Event');
      $this->set('subType', CRM_Utils_Array::value('event_type_id', $_POST));
      $this->assign('customDataSubType', CRM_Utils_Array::value('event_type_id', $_POST));
      $this->set('entityId', $entityID);

      CRM_Custom_Form_CustomData::preProcess($this, NULL, $this->_eventType, 1, 'Event', $entityID);
      CRM_Custom_Form_CustomData::buildQuickForm($this);
      CRM_Custom_Form_CustomData::setDefaultValues($this);
    }
  }

  /**
   * Set default values for the form.
   *
   * For edit/view mode he default values are retrieved from the database.
   */
  public function setDefaultValues() {
    $defaults = parent::setDefaultValues();

    // in update mode, we need to set custom data subtype to tpl
    if (!empty($defaults['event_type_id'])) {
      $this->assign('customDataSubType', $defaults['event_type_id']);
    }

    $this->_showHide = new CRM_Core_ShowHideBlocks();
    // Show waitlist features or event_full_text if max participants set
    if (!empty($defaults['max_participants'])) {
      $this->_showHide->addShow('id-waitlist');
      if (!empty($defaults['has_waitlist'])) {
        $this->_showHide->addShow('id-waitlist-text');
        $this->_showHide->addHide('id-event_full');
      }
      else {
        $this->_showHide->addHide('id-waitlist-text');
        $this->_showHide->addShow('id-event_full');
      }
    }
    else {
      $this->_showHide->addHide('id-event_full');
      $this->_showHide->addHide('id-waitlist');
      $this->_showHide->addHide('id-waitlist-text');
    }

    $this->_showHide->addToTemplate();
    $this->assign('elemType', 'table-row');

    $this->assign('description', CRM_Utils_Array::value('description', $defaults));

    // Provide suggested text for event full and waitlist messages if they're empty
    $defaults['event_full_text'] = CRM_Utils_Array::value('event_full_text', $defaults, ts('This event is currently full.'));

    $defaults['waitlist_text'] = CRM_Utils_Array::value('waitlist_text', $defaults, ts('This event is currently full. However you can register now and get added to a waiting list. You will be notified if spaces become available.'));
    $defaults['template_id'] = $this->_templateId;

    return $defaults;
  }

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    //need to assign custom data type and subtype to the template
    $this->assign('customDataType', 'Event');
    if ($this->_eventType) {
      $this->assign('customDataSubType', $this->_eventType);
    }

    $this->_first = TRUE;
    $this->applyFilter('__ALL__', 'trim');
    $attributes = CRM_Core_DAO::getAttribute('CRM_Event_DAO_Event');

    if ($this->_isTemplate) {
      $this->add('text', 'template_title', ts('Template Title'), $attributes['template_title'], TRUE);
    }

    if ($this->_action & CRM_Core_Action::ADD) {
      $eventTemplates = \Civi\Api4\Event::get(FALSE)
        ->addWhere('is_template', '=', TRUE)
        ->addWhere('is_active', '=', TRUE)
        ->execute()
        ->indexBy('id')
        ->column('template_title');
      if (CRM_Utils_System::isNull($eventTemplates) && !$this->_isTemplate) {
        $url = CRM_Utils_System::url('civicrm/admin/eventTemplate', ['reset' => 1]);
        CRM_Core_Session::setStatus(ts('If you find that you are creating multiple events with similar settings, you may want to use the <a href="%1">Event Templates</a> feature to streamline your workflow.', [1 => $url]), ts('Tip'), 'info');
      }
      if (!CRM_Utils_System::isNull($eventTemplates)) {
        $this->add('select', 'template_id', ts('From Template'), ['' => ts('- select -')] + $eventTemplates, FALSE, ['class' => 'crm-select2 huge']);
      }
      // Make sure this form redirects properly
      $this->preventAjaxSubmit();
    }

    // add event title, make required if this is not a template
    $this->add('text', 'title', ts('Event Title'), $attributes['event_title'], !$this->_isTemplate);

    $this->addSelect('event_type_id',
      ['onChange' => "CRM.buildCustomData( 'Event', this.value );"],
      TRUE
    );

    //CRM-7362 --add campaigns.
    $campaignId = NULL;
    if ($this->_id) {
      $campaignId = CRM_Core_DAO::getFieldValue('CRM_Event_DAO_Event', $this->_id, 'campaign_id');
    }
    CRM_Campaign_BAO_Campaign::addCampaign($this, $campaignId);

    $this->addSelect('default_role_id', [], TRUE);

    $this->addSelect('participant_listing_id', ['placeholder' => ts('Disabled'), 'option_url' => NULL]);

    $this->add('textarea', 'summary', ts('Event Summary'), $attributes['summary']);
    $this->add('wysiwyg', 'description', ts('Complete Description'), $attributes['event_description'] + ['preset' => 'civievent']);
    $this->addElement('checkbox', 'is_public', ts('Public Event'));
    $this->addElement('checkbox', 'is_share', ts('Add footer region with Twitter, Facebook and LinkedIn share buttons and scripts?'));
    $this->addElement('checkbox', 'is_map', ts('Include Map to Event Location'));

    $this->add('datepicker', 'start_date', ts('Start'), [], !$this->_isTemplate, ['time' => TRUE]);
    $this->add('datepicker', 'end_date', ts('End'), [], FALSE, ['time' => TRUE]);

    $this->add('number', 'max_participants', ts('Max Number of Participants'),
      ['onchange' => "if (this.value != '') {cj('#id-waitlist').show(); showHideByValue('has_waitlist','0','id-waitlist-text','table-row','radio',false); showHideByValue('has_waitlist','0','id-event_full','table-row','radio',true); return;} else {cj('#id-event_full, #id-waitlist, #id-waitlist-text').hide(); return;}"]
    );
    $this->addRule('max_participants', ts('Max participants should be a positive number'), 'positiveInteger');

    $participantStatuses = CRM_Event_PseudoConstant::participantStatus();
    $waitlist = 0;
    if (in_array('On waitlist', $participantStatuses) and in_array('Pending from waitlist', $participantStatuses)) {
      $this->addElement('checkbox', 'has_waitlist', ts('Offer a Waitlist?'), NULL, ['onclick' => "showHideByValue('has_waitlist','0','id-event_full','table-row','radio',true); showHideByValue('has_waitlist','0','id-waitlist-text','table-row','radio',false);"]);
      $this->add('textarea', 'waitlist_text', ts('Waitlist Message'), $attributes['waitlist_text']);
      $waitlist = 1;
    }
    $this->assign('waitlist', $waitlist);

    $this->add('textarea', 'event_full_text', ts('Message if Event Is Full'), $attributes['event_full_text']);

    $this->addElement('checkbox', 'is_active', ts('Is this Event Active?'));

    $this->addFormRule(['CRM_Event_Form_ManageEvent_EventInfo', 'formRule']);

    parent::buildQuickForm();
  }

  /**
   * Global validation rules for the form.
   *
   * @param array $values
   *
   * @return array
   *   list of errors to be posted back to the form
   */
  public static function formRule($values) {
    $errors = [];

    // Validate start/end date inputs
    $validateDates = \CRM_Utils_Date::validateStartEndDatepickerInputs('start_date', $values['start_date'], 'end_date', $values['end_date']);
    if ($validateDates !== TRUE) {
      $errors[$validateDates['key']] = $validateDates['message'];
    }

    return $errors;
  }

  /**
   * Process the form submission.
   */
  public function postProcess() {
    $params = array_merge($this->controller->exportValues($this->_name), $this->_submitValues);

    //format params
    $params['start_date'] = $params['start_date'] ?? NULL;
    $params['end_date'] = $params['end_date'] ?? NULL;
    $params['has_waitlist'] = CRM_Utils_Array::value('has_waitlist', $params, FALSE);
    $params['is_map'] = CRM_Utils_Array::value('is_map', $params, FALSE);
    $params['is_active'] = CRM_Utils_Array::value('is_active', $params, FALSE);
    $params['is_public'] = CRM_Utils_Array::value('is_public', $params, FALSE);
    $params['is_share'] = CRM_Utils_Array::value('is_share', $params, FALSE);
    $params['default_role_id'] = CRM_Utils_Array::value('default_role_id', $params, FALSE);
    $params['id'] = $this->_id;

    //merge params with defaults from templates
    if (!empty($params['template_id'])) {
      $params = array_merge(CRM_Event_BAO_Event::getTemplateDefaultValues($params['template_id']), $params);
    }

    $params['custom'] = CRM_Core_BAO_CustomField::postProcess($params,
      $this->_id,
      'Event'
    );

    // now that we have the event’s id, do some more template-based stuff
    if (!empty($params['template_id'])) {
      $event = CRM_Event_BAO_Event::copy($params['template_id'], $params);
    }
    else {
      $event = CRM_Event_BAO_Event::create($params);
    }

    $this->set('id', $event->id);

    $this->postProcessHook();

    if ($this->_action & CRM_Core_Action::ADD) {
      $url = 'civicrm/event/manage/location';
      $urlParams = "action=update&reset=1&id={$event->id}";
      // special case for 'Save and Done' consistency.
      if ($this->controller->getButtonName('submit') == '_qf_EventInfo_upload_done') {
        $url = 'civicrm/event/manage';
        $urlParams = 'reset=1';
        CRM_Core_Session::setStatus(ts("'%1' information has been saved.",
          [1 => $this->getTitle()]
        ), ts('Saved'), 'success');
      }

      CRM_Utils_System::redirect(CRM_Utils_System::url($url, $urlParams));
    }

    parent::endPostProcess();
  }

  /**
   * Return a descriptive name for the page, used in wizard header.
   *
   * @return string
   */
  public function getTitle() {
    return ts('Event Information and Settings');
  }

}
