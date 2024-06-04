<?php

/**
 * This is a helper class for the queue functionality
 * It is a singleton class because it will hold the queue object for our extension
 */
class CRM_CiviEmailEngagement_Queue {
  const QUEUE_NAME = 'civiemailengagement';

  /**
   * @var CRM_CiviEmailEngagement_Queue
   */
  private $queue;

  public static $singleton;

  /**
   * @return CRM_CiviEmailEngagement_Queue
   */
  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new CRM_CiviEmailEngagement_Queue();
    }
    return self::$singleton;
  }

  private function __construct() {
    $this->queue = CRM_Queue_Service::singleton()->create([
      'type' => 'SqlParallel',
      'name' => self::QUEUE_NAME,
      // do not flush queue upon creation
      'reset' => FALSE
    ]);
  }

  /**
   * @return CRM_Queue_Queue
   */
  public function getQueue() {
    return $this->queue;
  }

}