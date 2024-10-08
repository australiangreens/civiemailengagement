<?php
use CRM_CiviEmailEngagement_ExtensionUtil as E;
return [
  'name' => 'ContactEmailEngagement',
  'table' => 'civicrm_contact_email_engagement',
  'class' => 'CRM_CiviEmailEngagement_DAO_ContactEmailEngagement',
  'getInfo' => fn() => [
    'title' => E::ts('Contact Email Engagement'),
    'title_plural' => E::ts('Contact Email Engagements'),
    'description' => E::ts('Email engagement data for CiviCRM contacts'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => E::ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => E::ts('Unique ContactEmailEngagement ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'contact_id' => [
      'title' => E::ts('Contact ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => E::ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'date_last_click' => [
      'title' => E::ts('Date Last Click'),
      'sql_type' => 'timestamp',
      'input_type' => NULL,
      'description' => E::ts('Date of last relevant email clickthrough'),
    ],
    'date_first_click' => [
      'title' => E::ts('Date First Click'),
      'sql_type' => 'timestamp',
      'input_type' => NULL,
      'description' => E::ts('Date of first relevant email clickthrough'),
    ],
    'date_calculated' => [
      'title' => E::ts('Date Calculated'),
      'sql_type' => 'timestamp',
      'input_type' => NULL,
      'description' => E::ts('Date of last calculation of email engagement values'),
    ],
    'volume_emails_clicked' => [
      'title' => E::ts('Volume Emails Clicked'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'description' => E::ts('Number of mailings engaged with within reporting period'),
    ],
    'volume_emails_sent' => [
      'title' => E::ts('Volume Emails Sent'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'description' => E::ts('Number of mailings sent within reporting period'),
    ],
    'volume_emails_sent_30days' => [
      'title' => E::ts('Volume Emails Sent 30days'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'description' => E::ts('Number of mailings sent within last 30 days'),
    ],
  ],
];
