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
  public static function processEETask(CRM_Queue_TaskContext $ctx, $params) {
    try {
      \Drupal::logger('civiemailengagement')->info('Processing Email Engagement values for contact {contact_id}', ['contact_id' => $params['contact_id']]);
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
    $ee_period = Civi::settings()->get('civiemailengagement_period');

    // Construct the earliest date that defines our EE period/window
    $ee_earliest_date = new DateTime();
    $ee_earliest_date->sub(new DateInterval("P{$ee_period}M"));
    // Construct last 30 day window for recent volume calculation
    $now = new DateTime();
    $last30days = $now->sub(new DateInterval("P30D"));

    // Set up results object
    // recency - date of last click
    // frequency - number of mailings clicked in reporting period
    // volume - number of mailings in reporting period
    // volume_30days - number of mailings in last 30 days
    $result['contact_id'] = $contact_id;
    $result['recency'] = NULL;
    $result['frequency'] = NULL;
    $result['volume'] = NULL;
    $result['volume_last_30'] = NULL;

    // Get all trackable URL opens within the time range
    $opens = \Civi\Api4\MailingEventTrackableURLOpen::get(FALSE)
    ->addSelect('time_stamp', 'meq.mailing_id')
    ->addJoin('MailingEventQueue AS meq', 'INNER', ['event_queue_id', '=', 'meq.id'])
    ->addWhere('meq.contact_id', '=', $contact_id)
    ->addWhere('time_stamp', '>=', $ee_earliest_date->format('Y-m-d H:i:sP'))
    ->addOrderBy('time_stamp', 'ASC')
    ->execute();

    $opens = iterator_to_array($opens);

    // Retrieve all mailings delivered to contact within the time range
    $mailings = \Civi\Api4\MailingEventDelivered::get(FALSE)
      ->addSelect('time_stamp', 'meq.mailing_id')
      ->addJoin('MailingEventQueue AS meq', 'INNER', ['event_queue_id', '=', 'meq.id'])
      ->AddWhere('meq.contact_id', '=', $contact_id)
      ->addWhere('time_stamp', '>=', $ee_earliest_date->format('Y-m-d H:i:sP'))
      ->addOrderBy('time_stamp', 'ASC')
      ->execute();

    $mailings = iterator_to_array($mailings);

    // If there have been no mailings within the reporting period
    // delete any existing EE records and return an empty result
    if (empty($mailings)) {
      \Civi\Api4\ContactEmailEngagement::delete(FALSE)
      ->addWhere('contact_id', '=', $contact_id)
      ->execute();
      return $result;
    }

    // Calculate EE values
    if (count($opens)) {
      // Calculate recency and frequency if there's a trackable URL open
      // within the reporting period
      $date_first = $opens[0]['time_stamp'];
      $date_last = $opens[count($opens) - 1]['time_stamp'];
      $result['recency'] = (new DateTime())->diff(new DateTime($date_last))->days;
      $result['frequency'] = count(array_unique(array_column($opens, 'meq.mailing_id')));
    }
    // Count mailings in reporting period
    $result['volume'] = count(array_unique(array_column($mailings, 'meq.mailing_id')));
    // Count mailings in last 30 days
    $filtered_mailings = array_filter($mailings, function($item) use ($last30days) {
      $scheduled_date = new DateTime($item['time_stamp']);
      return $scheduled_date >= $last30days;
    });
    $result['volume_last_30'] = count(array_unique(array_column($filtered_mailings, 'meq.mailing_id')));

    // Upsert ContactEmailEngagement record
    $payload['contact_id'] = $contact_id;
    $payload['date_first_click'] = $date_first;
    $payload['date_last_click'] = $date_last;
    $payload['date_calculated'] = (new DateTimeImmutable())->format('Y-m-d H:i:sP');
    $payload['volume_emails_clicked'] = $result['frequency'];
    $payload['volume_emails_sent'] = $result['volume'];
    $payload['volume_emails_sent_30days'] = $result['volume_last_30'];
    $res = \Civi\Api4\ContactEmailEngagement::save(FALSE)
      ->setRecords([$payload])
      ->setMatch(['contact_id'])
      ->execute();

    // Return $result
    return $result;
  }

  /**
   * Finds expired ContactEmailEngagement records and queues them for recalculation.
   * Expired records are those where the date of the first trackable URL click
   * is earlier than "now - ee_period'
   */
  public static function refreshExpired(): int {
    $ee_period = Civi::settings()->get('civiemailengagement_period');
    $ee_earliest_date = new DateTime();
    $ee_earliest_date->sub(new DateInterval("P{$ee_period}M"));

    $expired_records = \Civi\Api4\ContactEmailEngagement::get(FALSE)
      ->addSelect('contact_id')
      ->addWhere('date_first_click', '<', $ee_earliest_date->format('Y-m-d H:i:sP'))
      ->setLimit(0)
      ->execute();

    if ($expired_records->rowCount) {
      $queue = CRM_CiviEmailEngagement_Queue::singleton()->getQueue();
      foreach ($expired_records as $record) {
        $params = ['contact_id' => $record['contact_id']];
        $task = new CRM_Queue_Task(
          ['CRM_CiviEmailEngagement_Utils', 'processEETask'],
          [$params]
        );
        $queue->createItem($task);
      }
    }
    return $expired_records->rowCount;
  }
}
