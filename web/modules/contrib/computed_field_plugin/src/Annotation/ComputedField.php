<?php

namespace Drupal\computed_field_plugin\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Computed field item annotation object.
 *
 * @see \Drupal\computed_field_plugin\Plugin\ComputedFieldManager
 * @see plugin_api
 *
 * @Annotation
 */
class ComputedField extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The base field type.
   *
   * @var string
   */
  public $type;

  /**
   * The entity type(s) that will have access to the computed field.
   *
   * Leave empty to allow all.
   *
   * @var array
   */
  public $entity_types;

  /**
   * The bundle(s) that will have access to the computed field.
   *
   * Leave empty to allow all bundles of the selected entity types.
   *
   * @var array
   */
  public $bundles;

}
