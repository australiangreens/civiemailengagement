<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'Cron:ContactEmailEngagement.Refreshexpired',
    'entity' => 'Job',
    'params' => [
      'version' => 3,
      'name' => 'CiviEmailEngagement find expired records',
      'description' => 'Checks for expired CiviEmailEngagement records and queues them for reprocessing',
      'run_frequency' => 'Daily',
      'api_entity' => 'ContactEmailEngagement',
      'api_action' => 'Refreshexpired',
      'parameters' => '',
    ],
  ],
];
