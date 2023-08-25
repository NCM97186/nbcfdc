<?php

namespace Drupal\computed_field_plugin\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Class ComputedRenderArrayItem.
 *
 * @package Drupal\computed_field_plugin\Field\FieldType
 *
 * @FieldType(
 *   id = "computed_render_array",
 *   label = @Translation("Computed render array"),
 *   default_formatter = "computed_render_array_formatter"
 * )
 */
class ComputedRenderArrayItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [];
  }

}
