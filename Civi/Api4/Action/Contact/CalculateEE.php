<?php

namespace Civi\Api4\Action\Contact;

use Civi\Api4\Generic\Result;

/**
 * Calculate Email Engagement values for the Contact. Uses CRM_CiviEmailEngagement_Utils::calculateEE
 * to upsert ContactEmailEngagement entity record.
 *
 * @method calculateEE()
 */
class CalculateEE extends \Civi\Api4\Generic\AbstractAction {

  /**
   * ID of contact
   *
   * @var int
   * @required
   */
  protected $contactId;

  public function _run(Result $result) {
    $data = \CRM_CiviEmailEngagement_Utils::calculateEE($this->contactId);
    $result[] = [
      'id' => $this->contactId,
      'recency' => $data['recency'],
      'frequency' => $data['frequency'],
      'volume' => $data['volume'],
      'volume_last_30' => $data['volume_last_30'],
    ];
  }
}

