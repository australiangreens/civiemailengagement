<?php

require_once 'civiemailengagement.civix.php';

use CRM_CiviEmailEngagement_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function civiemailengagement_civicrm_config(&$config): void {
  _civiemailengagement_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function civiemailengagement_civicrm_install(): void {
  _civiemailengagement_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function civiemailengagement_civicrm_enable(): void {
  _civiemailengagement_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function civiemailengagement_civicrm_navigationMenu(&$menu): void {
  _civiemailengagement_civix_insert_navigation_menu($menu, 'Administer/CiviModels', [
    'label' => E::ts('Email Engagement Model Settings'),
    'name' => 'civiemailengagement_settings',
    'url' => 'civicrm/admin/setting/civiemailengagement',
    'permission' => 'administer civiemailengagement',
    'operator' => 'OR',
    'separator' => 0,
  ]);
  _civiemailengagement_civix_navigationMenu($menu);
}

/**
 * Implements hook_civicrm_permission().
 */
function civiemailengagement_civicrm_permission(&$permissions) {
  $prefix = E::ts('CiviEmailEngagement extension: ');
  $permissions['administer civiemailengagement'] = [
    'label' => $prefix . E::ts('Administer CiviEmailEngagement'),
    'description' => E::ts('Manage CiviEmailEngagement settings')
  ];
  $permissions['access civiemailengagement'] = [
    'label' => $prefix . E::ts('Access CiviEmailEngagement'),
    'description' => E::ts('View CiviEmailEngagement data')
  ];
}

/**
 * Implements hook_displayCiviModelData().
 *
 * Builds data payload for CiviModels extension display
 *
 * @link https://github.com/australiangreens/
 */
function civiemailengagement_civimodels_displayCiviModelData($contact_id, &$data) {
  if (!CRM_Core_Permission::check('access civirfm')) {
    return;
  }
  $contactEE = \Civi\Api4\ContactEmailEngagement::get(FALSE)
  ->addSelect('id', 'contact_id', 'recency', 'frequency', 'monetary', 'date_calculated')
  ->addWhere('contact_id', '=', $contact_id)
  ->execute()
  ->first(); // we can safely assume there is only a single ContactRfm record per contact

  if (isset($contactRfm['date_calculated'])) {
    $civirfm = [
      'contact_id' => $contact_id,
      'recency' => $contactRfm['recency'],
      'frequency' => $contactRfm['frequency'],
      'monetary' => $contactRfm['monetary'],
      'date_calculated' => $contactRfm['date_calculated'],
      'rfm_time' => \Civi::settings()->get('civirfm_rfm_period'),
      'template' => 'CRM/Civirfm/Page/ContactEE.tpl'
    ];
    $data['civirfm'] = $civirfm;
  }
}

function civiemailengagement_create_queue_task($params) {
  $queue = CRM_CiviEmailEngagement_Queue::singleton()->getQueue();
  $task = new CRM_Queue_Task(
    ['CRM_CiviEmailEngagement_Utils', 'processEETask'],
    [$params],
    'Calculate Email Engagement values'
  );
  $queue->createItem($task);
}

/**
 * Implements hook_civicrm_postCommit().
 *
 * Every time a trackable URL is opened, queue up a job to calculate the
 * engagement values for the associated contact.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postCommit
 */
function civiemailengagement_civicrm_postCommit($op, $objectName, $objectId, $objectRef) {
  if ($op == 'create' && $objectName == 'MailingEventTrackableURLOpen') {
    $params = [
      'contact_id' => $objectId,
    ];
    civiemailengagement_create_queue_task($params);
  }
}

/**
 * Implements hook_civicrm_merge().
 *
 * Whenever contacts are merged, check to see if either contact has engagement data.
 * If so, delete it, then queue up a job to recalculate the values for the surviving contact.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_merge
 */
function civiemailengagement_civicrm_merge($type, &$data, $mainId = NULL, $otherId = NULL, $tables = NULL) {
  $result = \Civi\Api4\ContactEmailEngagement::get(FALSE)
    ->addSelect('id')
    ->addClause('OR', ['contact_id', '=', $mainId], ['contact_id', '=', $otherId])
    ->execute();
  if ($result->rowCount) {
    // Delete existing records
    foreach ($result as $ee_record) {
      \Civi\Api4\ContactEmailEngagement::delete(FALSE)
        ->addWhere('id', '=', $ee_record['id'])
        ->execute();
    }
    $params = [
      'contact_id' => $mainId,
    ];
    civiemailengagement_create_queue_task($params);
  }
  return;
}