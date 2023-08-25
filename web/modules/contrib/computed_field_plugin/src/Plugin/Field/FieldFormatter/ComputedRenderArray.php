<?php

namespace Drupal\computed_field_plugin\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Class ComputedRenderArray.
 *
 * @package Drupal\computed_field_plugin\Plugin\Field\FieldFormatter
 *
 * @FieldFormatter(
 *   id = "computed_render_array_formatter",
 *   label = @Translation("Default"),
 *   field_types = {
 *     "computed_render_array"
 *   }
 * )
 */
class ComputedRenderArray extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = $item->getValue();
    }
    return $elements;
  }

}
