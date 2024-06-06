<?php

namespace Civi\Api4\Service\Spec\Provider;

use Civi\Api4\Service\Spec\FieldSpec;
use Civi\Api4\Service\Spec\RequestSpec;

/**
 * @service
 * @internal
 */
class ContactEmailEngagementGetSpecProvider extends \Civi\Core\Service\AutoService implements Generic\SpecProviderInterface {

  /**
   * @param \Civi\Api4\Service\Spec\RequestSpec $spec
   */
  public function modifySpec(RequestSpec $spec) {
    // Recency field
    $field = new FieldSpec('recency', 'ContactEmailEngagement', 'Integer');
    $field->setLabel(ts('Recency (days)'))
      ->setTitle(ts('Recency (days)'))
      ->setColumnName('date_last_click')
      ->setInputType('Number')
      ->setDescription(ts('Time since last mailing clickthrough (days)'))
      ->setType('Extra')
      ->setReadonly(TRUE)
      ->setSqlRenderer([__CLASS__, 'calculateRecency']);
    $spec->addFieldSpec($field);
  }

  /**
   * @param string $entity
   * @param string $action
   *
   * @return bool
   */
  public function applies($entity, $action) {
    return $entity === 'ContactEmailEngagement' && $action === 'get';
  }

  /**
   * Generate SQL for recency field
   * @param array $field
   * @return string
   */
  public static function calculateRecency(array $field): string {
    return "TIMESTAMPDIFF(DAY, {$field['sql_name']}, CURDATE())";
  }
}