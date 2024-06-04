<?php
namespace Civi\Api4;

/**
 * ContactEmailEngagement entity.
 *
 * Provided by the CiviEmailEngagement extension.
 *
 * @package Civi\Api4
 */
class ContactEmailEngagement extends Generic\DAOEntity {

  /**
   * @param bool $checkPermissions
   * @return Action\ContactEmailEngagement\RefreshExpired
   */
  public static function refreshExpired($checkPermissions = TRUE) {
    return (new Action\ContactEmailEngagement\RefreshExpired(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\ContactEmailEngagement\RunQueue
   */
  public static function runQueue($checkPermissions = TRUE) {
    return (new Action\ContactEmailEngagement\RunQueue(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }
}