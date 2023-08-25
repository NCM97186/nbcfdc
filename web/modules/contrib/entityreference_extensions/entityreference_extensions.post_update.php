<?php

/**
 * @file
 * Post-update functions entityreference_extensions.
 */

use Drupal\Core\Config\Entity\ConfigEntityUpdater;
use Drupal\Core\Entity\Display\EntityDisplayInterface;
use Drupal\entityreference_extensions\Plugin\Field\FieldFormatter\EntityReferenceEntityDeltaFormatter;
use Drupal\entityreference_extensions\Plugin\Field\FieldFormatter\EntityReferenceIdDeltaFormatter;
use Drupal\entityreference_extensions\Plugin\Field\FieldFormatter\EntityReferenceLabelDeltaFormatter;

/**
 * Update formatter configuration for Issue #3110486.
 */
function entityreference_extensions_post_update_new_schema(&$sandbox) {
  /*
   * The existing configurations options (delta, reverse, offset)
   * need to be saved according to an updated schema. This was modeled
   * extremely closely on the core update function:
   * text_post_update_add_required_summary_flag_form_display().
   */
  $config_entity_updater = \Drupal::classResolver(ConfigEntityUpdater::class);
  /** @var \Drupal\Core\Field\FormatterPluginManager $field_formatter_manager */
  $field_formatter_manager = \Drupal::service('plugin.manager.field.formatter');

  $formatter_callback = function (EntityDisplayInterface $display) use ($field_formatter_manager) {
    $needs_save = FALSE;
    foreach ($display->getComponents() as $field_name => $component) {
      if (empty($component['type'])) {
        continue;
      }

      $plugin_definition = $field_formatter_manager->getDefinition($component['type'], FALSE);
      if (is_a($plugin_definition['class'], EntityReferenceIdDeltaFormatter::class, TRUE) || is_a($plugin_definition['class'], EntityReferenceLabelDeltaFormatter::class, TRUE) || is_a($plugin_definition['class'], EntityReferenceEntityDeltaFormatter::class, TRUE)) {
        // Do not touch configuration if new schema is already set
        if (!isset($component['settings']['limit'])) {
          // Delta becomes limit.number and we add 1.
          if (isset($component['settings']['delta']) && is_numeric($component['settings']['delta'])) {
            $component['settings']['limit']['number'] = $component['settings']['delta'] + 1;
            unset($component['settings']['delta']);
          }
          else {
            // Here if delta is not set or delta is 'all'.
            $component['settings']['limit']['number'] = '';
          }
          // Reverse becomes limit.reverse.
          if (isset($component['settings']['reverse'])) {
            $component['settings']['limit']['reverse'] = $component['settings']['reverse'];
            unset($component['settings']['reverse']);
          }
          else {
            $component['settings']['limit']['reverse'] = FALSE;
          }
          // Offset becomes limit.offset.
          if (isset($component['settings']['offset'])) {
            $component['settings']['limit']['offset'] = $component['settings']['offset'];
            unset($component['settings']['offset']);
          }
          else {
            $component['settings']['limit']['offset'] = 0;
          }
          // Set some new values to the defaults.
          $component['settings']['limit']['limit_before_sort'] = FALSE;
          $component['settings']['sort'] = [
            'field' => '',
            'asc' => TRUE,
          ];
          $display->setComponent($field_name, $component);
          $needs_save = TRUE;
        }
      }
    }

    return $needs_save;
  };

  $config_entity_updater->update($sandbox, 'entity_view_display', $formatter_callback);
}
