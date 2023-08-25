<?php

namespace Drupal\computed_field_plugin\Traits;

use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Trait ComputedSingleItemTrait.
 *
 * @package Drupal\computed_field_plugin\Traits
 * @property array $list
 */
trait ComputedSingleItemTrait {

  use ComputedItemListTrait;

  /**
   * {@inheritdoc}
   */
  protected function computeValue() {
    $this->list[0] = $this->createItem(0, $this->singleComputeValue());
  }

  /**
   * Handle single value to help Bec sleep at night.
   *
   * @return mixed
   *   Returns the computed value.
   */
  abstract protected function singleComputeValue();

}
