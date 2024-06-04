<?php

/**
 *
 * This is the extension class that holds the core functionality of the extension.
 * We define it here so it's easily accessible in a variety of places programmatically
 * and manually, via API and other methods.
 */

class CRM_CiviEmailEngagement_Utils {

  /**
   * Callback function for queue/task processing
   * Invokes self::calculateEE on the given contact_id
   *
   * @param CRM_Queue_TaskContext $ctx
   * @param array $params
   * @return bool
   */
  public static function calculateEECallback(CRM_Queue_TaskContext $ctx, $params) {
    try {
      $contact_id = $params['contact_id'];
      $result = self::calculateEE($contact_id);
      return TRUE;
    } catch (Exception $e) {
      Civi::log()->error('Error calculating Email Engagement values for contact {contact_id}', ['contact_id' => $contact_id]);
        return FALSE;
    }
  }

/**
 * Calculate the EE values for the supplied contact_id.
 * If values can be calculated, upsert the appropriate ContactEmailEngagement record.
 *
 * @param int $contact_id
 * @return array
 */
  public static function calculateEE($contact_id): array {
    // Get extension settings
    $ee_period = Civi::settings()->get('civiemailengagement_ee_period');

    // Construct the earliest date that defines our EE period/window
    $ee_earliest_date = new DateTime();
    $ee_earliest_date->sub(new DateInterval("P{$ee_period}D"));

    // Get all trackable URL opens within the time range
    $opens = \Civi\Api4\MailingEventTrackableURLOpen::get(TRUE)
    ->addSelect('meq.contact_id', 'meq.mailing_id')
    ->addJoin('MailingEventQueue AS meq', 'INNER', ['event_queue_id', '=', 'meq.id'])
    ->execute();

    $opens = iterator_to_array($opens);

    // If there are no relevant opens, delete any existing EE records
    if (empty($opens)) {
      \Civi\Api4\ContactEmailEngagement::delete(TRUE)
      ->addWhere('contact_id', '=', $contact_id)
      ->execute();
      return [];
    }
  }
}