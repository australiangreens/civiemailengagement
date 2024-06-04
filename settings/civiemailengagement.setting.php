<?php
use CRM_CiviEmailEngagement_ExtensionUtil as E;

/*
 * Settings metadata file
 */

return [
  'civiemailengagement_period' => [
    'name' => 'civiemailengagement_period',
    'filter' => 'civiemailengagement',
    'type' => 'String',
    'add' => '5.70',
    'is_contact' => 0,
    'description' => E::ts('The time period for calculating Email Engagement data (in months)'),
    'title' => E::ts('Model reporting period'),
    'default' => '12',
    'html_type' => 'text',
    'html_attributes' => [
      'size' => '5',
      'spellcheck' => 'false',
      'required' => 'true',
    ],
    'settings_pages' => ['civiemailengagement' => ['weight' => 10]],
  ],
];