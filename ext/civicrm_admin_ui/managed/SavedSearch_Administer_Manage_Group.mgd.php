<?php

use CRM_CivicrmAdminUi_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_Manage_groups',
    'entity' => 'SavedSearch',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Manage_groups',
        'label' => E::ts('Manage groups'),
        'form_values' => NULL,
        'mapping_id' => NULL,
        'search_custom_id' => NULL,
        'api_entity' => 'Group',
        'api_params' => [
          'version' => 4,
          'select' => [
            'id',
            'title',
            'created_id.display_name',
            'description',
            'group_type:label',
            'visibility:label',
            'COUNT(Group_GroupContact_Contact_01.display_name) AS COUNT_Group_GroupContact_Contact_01_display_name',
            'saved_search_id',
            'is_active',
            'frontend_title',
            'name',
            'parents:label',
          ],
          'orderBy' => [],
          'where' => [
            [
              'is_hidden',
              '=',
              FALSE,
            ],
          ],
          'groupBy' => [
            'id',
          ],
          'join' => [
            [
              'Contact AS Group_GroupContact_Contact_01',
              'LEFT',
              'GroupContact',
              [
                'id',
                '=',
                'Group_GroupContact_Contact_01.group_id',
              ],
              [
                'Group_GroupContact_Contact_01.status:name',
                '=',
                '"Added"',
              ],
            ],
          ],
          'having' => [],
        ],
        'expires_date' => NULL,
        'description' => NULL,
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'SavedSearch_Manage_groups_SearchDisplay_Manage_groups',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Manage_groups',
        'label' => E::ts('Manage groups'),
        'saved_search_id.name' => 'Manage_groups',
        'type' => 'table',
        'settings' => [
          'description' => NULL,
          'sort' => [
            [
              'title',
              'ASC',
            ],
          ],
          'limit' => 50,
          'pager' => [
            'show_count' => TRUE,
            'expose_limit' => TRUE,
            'hide_single' => TRUE,
          ],
          'placeholder' => 5,
          'columns' => [
            [
              'type' => 'field',
              'key' => 'title',
              'dataType' => 'String',
              'label' => E::ts('Title'),
              'sortable' => TRUE,
              'rewrite' => '',
              'editable' => TRUE,
              'icons' => [
                [
                  'icon' => 'fa-wpforms',
                  'side' => 'left',
                  'if' => [
                    'saved_search_id',
                    'IS NOT EMPTY',
                  ],
                ],
              ],
            ],
            [
              'type' => 'field',
              'key' => 'frontend_title',
              'dataType' => 'String',
              'label' => E::ts('Public Title'),
              'sortable' => TRUE,
              'rewrite' => '',
              'editable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'is_active',
              'dataType' => 'Boolean',
              'label' => E::ts('Enabled'),
              'sortable' => TRUE,
              'editable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'COUNT_Group_GroupContact_Contact_01_display_name',
              'dataType' => 'Integer',
              'label' => E::ts('Count'),
              'sortable' => TRUE,
              'rewrite' => '{if "[saved_search_id]"}{else}[COUNT_Group_GroupContact_Contact_01_display_name]{/if}',
              'icons' => [
                [
                  'icon' => 'fa-question',
                  'side' => 'left',
                  'if' => [
                    'saved_search_id',
                    'IS NOT EMPTY',
                  ],
                ],
              ],
            ],
            [
              'type' => 'field',
              'key' => 'created_id.display_name',
              'dataType' => 'String',
              'label' => E::ts('Created By'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Contact',
                'action' => 'view',
                'join' => 'created_id',
                'target' => '_blank',
              ],
              'title' => E::ts('View Contact'),
            ],
            [
              'type' => 'field',
              'key' => 'description',
              'dataType' => 'Text',
              'label' => E::ts('Description'),
              'sortable' => TRUE,
              'editable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'group_type:label',
              'dataType' => 'String',
              'label' => E::ts('Group Type'),
              'sortable' => TRUE,
              'editable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'visibility:label',
              'dataType' => 'String',
              'label' => E::ts('Visibility'),
              'sortable' => TRUE,
              'editable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'parents:label',
              'dataType' => 'Text',
              'label' => E::ts('Parents'),
              'sortable' => TRUE,
              'editable' => TRUE,
            ],
            [
              'links' => [
                [
                  'entity' => '',
                  'action' => '',
                  'join' => '',
                  'target' => '',
                  'icon' => 'fa-external-link',
                  'text' => E::ts('Contacts'),
                  'style' => 'default',
                  'path' => 'civicrm/group/search?reset=1&force=1&context=smog&gid=[id]&component_mode=1',
                  'condition' => [],
                ],
                [
                  'path' => 'civicrm/group/edit?reset=1&action=update&id=[id]',
                  'icon' => 'fa-cog',
                  'text' => E::ts('Settings'),
                  'style' => 'default',
                  'condition' => [],
                  'entity' => '',
                  'action' => '',
                  'join' => '',
                  'target' => 'crm-popup',
                ],
              ],
              'type' => 'links',
              'alignment' => 'text-right',
            ],
            [
              'text' => '',
              'style' => 'default',
              'size' => 'btn-xs',
              'icon' => 'fa-bars',
              'links' => [
                [
                  'entity' => 'Group',
                  'action' => 'delete',
                  'join' => '',
                  'target' => 'crm-popup',
                  'icon' => 'fa-trash',
                  'text' => E::ts('Remove Group'),
                  'style' => 'danger',
                  'path' => '',
                  'condition' => [],
                ],
              ],
              'type' => 'menu',
              'alignment' => 'text-right',
            ],
          ],
          'actions' => FALSE,
          'classes' => [
            'table',
            'table-striped',
          ],
          'addButton' => [
            'path' => 'civicrm/group/add?reset=1',
            'text' => E::ts('Add Group'),
            'icon' => 'fa-plus',
          ],
          'cssRules' => [
            [
              'disabled',
              'is_active',
              '=',
              FALSE,
            ],
          ],
        ],
        'acl_bypass' => FALSE,
      ],
      'match' => [
        'saved_search_id',
        'name',
      ],
    ],
  ],
];
