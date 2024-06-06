<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'Cron:ContactEmailEngagement.Runqueue',
    'entity' => 'Job',
    'params' => [
      'version' => 3,
      'name' => 'Call ContactEmailEngagement.Runqueue API',
      'description' => 'Call ContactEmailEngagement.Runqueue API',
      'run_frequency' => 'Hourly',
      'api_entity' => 'ContactEmailEngagement',
      'api_action' => 'Runqueue',
      'parameters' => '',
    ],
  ],
];
