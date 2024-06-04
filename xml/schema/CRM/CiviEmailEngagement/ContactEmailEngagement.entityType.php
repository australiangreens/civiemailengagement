<?php
// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
return [
  [
    'name' => 'ContactEmailEngagement',
    'class' => 'CRM_CiviEmailEngagement_DAO_ContactEmailEngagement',
    'table' => 'civicrm_contact_email_engagement',
  ],
];
