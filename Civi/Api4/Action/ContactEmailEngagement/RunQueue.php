<?php

namespace Civi\Api4\Action\ContactEmailEngagement;

use Civi\Api4\Generic\Result;
use CRM_CiviEmailEngagement_ExtensionUtil as E;
use CRM_CiviEmailEngagement_Queue;
use CRM_Queue_Runner;

/**
 * Process queued jobs.
 *
 * @method runQueue()
 */
class RunQueue extends \Civi\Api4\Generic\AbstractAction {

  /**
   * Maximum runtime for queue processing (seconds)
   *
   * @var int
   */
  protected $maxRunTime = 600;

  public function _run(Result $result) {
    $queue = CRM_CiviEmailEngagement_Queue::singleton()->getQueue();
    $runner = new CRM_Queue_Runner([
      'title' => E::ts('CiviEmailEngagement Queue Runner'),
      'queue' => $queue,
      'errorMode' => CRM_Queue_Runner::ERROR_CONTINUE,
    ]);

    $stopTime = time() + $this->maxRunTime;
    $continue = TRUE;
    while (time() < $stopTime && $continue) {
      $output = $runner->runNext();
      if (!$output['is_continue']) {
        // all items in the queue are processed
        $continue = FALSE;
      }
      $result[] = $output;
    }
  }
}