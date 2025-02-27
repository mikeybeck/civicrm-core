{*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
*}
{* This template is used for adding/scheduling reminders.  *}
<div class="crm-block crm-form-block crm-scheduleReminder-form-block">
 <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>

{if $action eq 8}
  <div class="messages status no-popup">
      {icon icon="fa-info-circle"}{/icon}
        {ts 1=$reminderName}WARNING: You are about to delete the Reminder titled <strong>%1</strong>.{/ts} {ts}Do you want to continue?{/ts}
  </div>
{else}
  <table class="form-layout-compressed">
    <tr class="crm-scheduleReminder-form-block-title">
        <td class="right">{$form.title.label}</td><td colspan="3">{$form.title.html}</td>
    </tr>
     <tr>
        <td class="label">{$form.entity.label}</td>
        <td>{$form.entity.html}</td>
    </tr>

    <tr class="crm-scheduleReminder-form-block-when">
        <td class="right">{$form.start_action_offset.label}</td>
        <td colspan="3">{$form.absolute_date.html} <strong id='OR'>{ts}OR{/ts}</strong><br /></td>
    </tr>

    <tr id="relativeDate" class="crm-scheduleReminder-form-block-description">
        <td class="right"></td>
        <td colspan="3">
          {$form.start_action_offset.html}&nbsp;&nbsp;&nbsp;{$form.start_action_unit.html}&nbsp;&nbsp;&nbsp;{$form.start_action_condition.html}&nbsp;&nbsp;&nbsp;{$form.start_action_date.html}
          {if $context === "event"}&nbsp;{help id="relative_absolute_schedule_dates"}{/if}
        </td>
    </tr>
    <tr id="recordActivity" class="crm-scheduleReminder-form-block-record_activity"><td class="label" width="20%">{$form.record_activity.label}</td>
        <td>{$form.record_activity.html}</td>
    </tr>
    <tr id="relativeDateRepeat" class="crm-scheduleReminder-form-block-is_repeat"><td class="label" width="20%">{$form.is_repeat.label}</td>
        <td>{$form.is_repeat.html}</td>
    </tr>
    <tr id="repeatFields" class="crm-scheduleReminder-form-block-repeatFields"><td></td><td>
        <table class="form-layout-compressed">
          <tr class="crm-scheduleReminder-form-block-repetition_frequency_interval">
            <td class="label">{$form.repetition_frequency_interval.label} <span class="crm-marker">*</span>&nbsp;&nbsp;{$form.repetition_frequency_interval.html}</td>
          <td>{$form.repetition_frequency_unit.html}</td>
          </tr>
          <tr class="crm-scheduleReminder-form-block-repetition_frequency_interval">
             <td class="label">{$form.end_frequency_interval.label} <span class="crm-marker">*</span>&nbsp;&nbsp;{$form.end_frequency_interval.html}
           <td>{$form.end_frequency_unit.html}&nbsp;&nbsp;&nbsp;{$form.end_action.html}&nbsp;&nbsp;&nbsp;{$form.end_date.html}</td>
          </tr>
        </table>
     </td>
    </tr>
    <tr class="crm-scheduleReminder-effective_start_date">
      <td class="right">{$form.effective_start_date.label}</td>
      <td colspan="3">{$form.effective_start_date.html} <div class="description">{ts}Earliest trigger date to <em>include</em>.{/ts}</div></td>
    </tr>
    <tr class="crm-scheduleReminder-effective_end_date">
      <td class="right">{$form.effective_end_date.label}</td>
      <td colspan="3">{$form.effective_end_date.html} <div class="description">{ts}Earliest trigger date to <em>exclude</em>.{/ts}</div></td>
    </tr>
    <tr>
      <td class="label" width="20%">{$form.from_name.label}</td>
      <td>{$form.from_name.html}&nbsp;&nbsp;{help id="id-from_name_email"}</td>
    </tr>
    <tr>
      <td class="label" width="20%">{$form.from_email.label}</td>
      <td>{$form.from_email.html}&nbsp;&nbsp;</td>
    </tr>
    <tr class="crm-scheduleReminder-form-block-recipient">
      <td id="recipientLabel" class="right">{$form.recipient.label}</td><td colspan="3">{$form.limit_to.html}&nbsp;{help id="limit_to" class="limit_to" title=$form.recipient.label}{$form.recipient.html}&nbsp;{help id="recipient" class="recipient" title=$recipientLabels.activity}</td>
    </tr>
    <tr id="recipientList" class="crm-scheduleReminder-form-block-recipientListing recipient">
        <td class="right">{$form.recipient_listing.label}</td><td colspan="3">{$form.recipient_listing.html}</td>
    </tr>
    <tr id="recipientManual" class="crm-scheduleReminder-form-block-recipient_manual_id recipient">
        <td class="label">{$form.recipient_manual_id.label}</td>
        <td>{$form.recipient_manual_id.html}</td>
    </tr>

    <tr id="recipientGroup" class="crm-scheduleReminder-form-block-recipient_group_id recipient">
        <td class="label">{$form.group_id.label}</td>
        <td>{$form.group_id.html}</td>
    </tr>
    {if !empty($form.mode)}
    <tr id="msgMode" class="crm-scheduleReminder-form-block-mode">
      <td class="label">{$form.mode.label}</td>
      <td>{$form.mode.html}</td>
    </tr>
    {/if}
    {if !empty($multilingual)}
    <tr class="crm-scheduleReminder-form-block-filter-contact-language">
      <td class="label">{$form.filter_contact_language.label}</td>
      <td>{$form.filter_contact_language.html} {help id="filter_contact_language"}</td>
    </tr>
    <tr class="crm-scheduleReminder-form-block-communication-language">
      <td class="label">{$form.communication_language.label}</td>
      <td>{$form.communication_language.html} {help id="communication_language"}</td>
    </tr>
    {/if}
    <tr class="crm-scheduleReminder-form-block-active">
      <td class="label">{$form.is_active.label}</td>
      <td>{$form.is_active.html}</td>
    </tr>
  </table>
  <fieldset id="email" class="crm-collapsible" style="display: block;">
    <legend class="collapsible-title">{ts}Email Screen{/ts}</legend>
      <div>
       <table id="email-field-table" class="form-layout-compressed">
         <tr class="crm-scheduleReminder-form-block-template">
            <td class="label">{$form.template.label}</td>
            <td>{$form.template.html}</td>
         </tr>
         <tr class="crm-scheduleReminder-form-block-subject">
            <td class="label">{$form.subject.label}</td>
            <td>
              {$form.subject.html|crmAddClass:huge}
              <input class="crm-token-selector big" data-field="subject" />
              {help id="id-token-subject" tplFile=$tplFile isAdmin=$isAdmin file="CRM/Contact/Form/Task/Email.hlp"}
            </td>
         </tr>
       </table>
       {include file="CRM/Contact/Form/Task/EmailCommon.tpl" upload=1 noAttach=1}
    </div>
    </fieldset>
    {if !empty($sms)}
      <fieldset id="sms" class="crm-collapsible"><legend class="collapsible-title">{ts}SMS Screen{/ts}</legend>
        <div>
        <table id="sms-field-table" class="form-layout-compressed">
          <tr id="smsProvider" class="crm-scheduleReminder-form-block-sms_provider_id">
            <td class="label">{$form.sms_provider_id.label}</td>
            <td>{$form.sms_provider_id.html}</td>
          </tr>
          <tr class="crm-scheduleReminder-form-block-sms-template">
            <td class="label">{$form.SMStemplate.label}</td>
            <td>{$form.SMStemplate.html}</td>
          </tr>
        </table>
        {include file="CRM/Contact/Form/Task/SMSCommon.tpl" upload=1 noAttach=1}
    <div>
  </fieldset>
  {/if}

{include file="CRM/common/showHideByFieldValue.tpl"
    trigger_field_id    = "is_repeat"
    trigger_value       = "true"
    target_element_id   = "repeatFields"
    target_element_type = "table-row"
    field_type          = "radio"
    invert              = "false"
}

{include file="CRM/common/showHideByFieldValue.tpl"
    trigger_field_id    ="recipient"
    trigger_value       = 'manual'
    target_element_id   ="recipientManual"
    target_element_type ="table-row"
    field_type          ="select"
    invert              = 0
}

{include file="CRM/common/showHideByFieldValue.tpl"
    trigger_field_id    ="recipient"
    trigger_value       = 'group'
    target_element_id   ="recipientGroup"
    target_element_type ="table-row"
    field_type          ="select"
    invert              = 0
}

{literal}
  <script type='text/javascript'>
    CRM.$(function($) {
      var $form = $('form.{/literal}{$form.formClass}{literal}'),
        recipientMapping = eval({/literal}{$recipientMapping}{literal});

      $('#absolute_date', $form).change(function() {
        $('.crm-scheduleReminder-effective_start_date, .crm-scheduleReminder-effective_end_date').toggle(($(this).val() === null));
      });
      $('#start_action_offset', $form).change(function() {
        $('.crm-scheduleReminder-effective_start_date, .crm-scheduleReminder-effective_end_date').toggle(($(this).val() !== null));
      });

      $('#absolute_date_display', $form).change(function() {
        if($(this).val()) {
          $('#relativeDate, #relativeDateRepeat, #repeatFields, #OR', $form).hide();
        } else {
          $('#relativeDate, #relativeDateRepeat, #OR', $form).show();
        }
      });
      if ($('#absolute_date_display', $form).val()) {
        $('#relativeDate, #relativeDateRepeat, #repeatFields, #OR', $form).hide();
      }

      loadMsgBox();
      $('#mode', $form).change(loadMsgBox);

      function populateRecipient() {
        var mappingID = $('#entity_0', $form).val() || $('[name^=mappingID]', $form).val();
        var recipient = $("#recipient", $form).val();
        $("#recipientList", $form).hide();
        if ($('#limit_to').val() != '' ) {
          $.getJSON(CRM.url('civicrm/ajax/recipientListing'), {mappingID: mappingID, recipientType: recipient},
            function (result) {
              if (!CRM._.isEmpty(result.recipients)) {
                CRM.utils.setOptions($('#recipient_listing', $form), result.recipients);
                $("#recipientList", $form).show();
              }
            }
          );
        }

        showHideLimitTo();
      }

      // CRM-14070 Hide limit-to when entity is activity
      function showHideLimitTo() {
        // '1' is the value of "Activity" in the entity select box.
        $('#limit_to', $form).toggle(!($('#entity_0', $form).val() == '1'));
        if ($('#entity_0', $form).val() != '1' || !($('#entity_0').length)) {
          // Some Event entity is selected.
          if (['2', '3', '5'].includes($('#entity_0', $form).val())) {
            $('#limit_to option[value="0"]', $form).attr('disabled','disabled').removeAttr('selected');
          }
          else {
            $('#limit_to option[value="0"]', $form).removeAttr('disabled');
          }
          // Anything but Activity is selected.
          if ($('#limit_to', $form).val() == '') {
            $('tr.recipient:visible, #recipientList, #recipient, a.recipient').hide();
            $('a.limit_to').show();
          }
          else {
            $('a.limit_to, a.recipient').show();
            $('#recipient').css("margin-left", "12px");
          }
          $("label[for='recipient']").text('{/literal}{$recipientLabels.other}{literal}');
        }
        else {
          // Activity is selected.
          $('#recipient, a.recipient').show()
          $('#recipient').css("margin-left", "-2px");
          $('a.limit_to').hide();
          $("label[for='recipient']").text('{/literal}{$recipientLabels.activity}{literal}');
        }
      }

      $('#recipient', $form).change(populateRecipient);

      {/literal}{if !$context}{literal}
        var entity = $('#entity_0', $form).val();
        if (!(entity === '2' || entity === '3')) {
          $('#recipientList', $form).hide();
         }

        $('#entity_0, #limit_to', $form).change(buildSelects);

        buildSelects();

        function buildSelects() {
          var mappingID = $('#entity_0', $form).val();
          var isLimit = $('#limit_to', $form).val();

          $.getJSON(CRM.url('civicrm/ajax/mapping'), {mappingID: mappingID, isLimit: isLimit},
            function (result) {
              CRM.utils.setOptions($('#start_action_date', $form), result.sel4);
              CRM.utils.setOptions($('#end_date', $form), result.sel4);
              CRM.utils.setOptions($('#recipient', $form), result.sel5);
              recipientMapping = result.recipientMapping;
              populateRecipient();
            }
          );
        }
      {/literal}{else}{literal}
        populateRecipient();
        $('#limit_to', $form).change(populateRecipient);
      {/literal}{/if}{literal}

      function loadMsgBox() {
        if (cj('#mode').val() == 'Email' || cj('#mode').val() == 0){
          cj('#sms').hide();
          cj('#email').show();
        }
        else if (cj('#mode').val() == 'SMS'){
          cj('#email').hide();
          cj('#sms').show();
          showSaveUpdateChkBox('SMS');
        }
        else if (cj('#mode').val() == 'User_Preference'){
          cj('#email').show();
          cj('#sms').show();
          showSaveUpdateChkBox('SMS');
        }
      }

    });
  </script>
{/literal}

{/if}

 <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
</div>
