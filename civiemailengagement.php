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
  Civi::service('dispatcher')->addListener('hook_civicrm_navigationMenu', 'civiemailengagement_symfony_navigationMenu', 100);
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
function civiemailengagement_symfony_navigationMenu($event): void {
  $hook_values = $event->getHookValues();
  $menu = &$hook_values[0];
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
  ->addSelect('recency', 'volume_emails_clicked', 'volume_emails_sent', 'volume_emails_sent_30days', 'date_calculated')
  ->addWhere('contact_id', '=', $contact_id)
  ->execute()
  ->first(); // we can safely assume there is only a single EE record per contact

  if (isset($contactEE['date_calculated'])) {
    $civiee = [
      'contact_id' => $contact_id,
      'recency' => $contactEE['recency'],
      'frequency' => $contactEE['volume_emails_clicked'],
      'volume' => $contactEE['volume_emails_sent'],
      'volume_last_30' => $contactEE['volume_emails_sent_30days'],
      'date_calculated' => $contactEE['date_calculated'],
      'ee_period' => \Civi::settings()->get('civiemailengagement_period'),
      'template' => 'CRM/CiviEmailEngagement/Page/ContactEmailEngagement.tpl'
    ];
    $data['civiemailengagement'] = $civiee;
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
    $result = \Civi\Api4\MailingEventTrackableURLOpen::get(FALSE)
      ->addSelect('meq.contact_id')
      ->addJoin('MailingEventQueue AS meq', 'INNER', ['event_queue_id', '=', 'meq.id'])
      ->addWhere('id', '=', $objectId)
      ->execute()
      ->first();
    $contact_id = $result['meq.contact_id'];
    $params = [
      'contact_id' => $contact_id,
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