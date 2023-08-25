<?php

namespace Drupal\entityreference_extensions;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Trait EntityReferenceDeltaFilterTrait.
 *
 * Provides a filter callback and other helpers that are used by
 * multiple classes that do not and can not inherit from the same parent.
 *
 * @package Drupal\entityreference_extensions
 */
trait EntityReferenceDeltaFilterTrait {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'limit' => [
        'number' => '',
        'offset' => '',
        'reverse' => FALSE,
        'limit_before_sort' => FALSE,
      ],
      'sort' => [
        'field' => '',
        'asc' => TRUE,
      ],
      'display' => [
        'enable' => FALSE,
        'number' => 1,
        'view_mode' => 'default'
      ]
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    $elements['limit'] = $this->limitSetting();
    $elements['sort'] = $this->sortSetting();
    if ($this->getPluginId() == 'entity_reference_entity_view_delta') {
      $elements['display'] = $this->displaySetting();
    }
    $elements['examples'] = [
      '#type' => 'details',
      '#title' => $this->t('Examples'),
      0 => [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('If you want to sort all the items and then display the top three, select 3 as "Items to Show" and un-check the "Limit Before Sorting" box.'),
      ],
      1 => [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('If you want to take the first three items, sort them, and display them, you should select 3 as "Items to Show" and check the "Limit Before Sorting" box.'),
      ],
      2 => [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('If you want to ignore the last three items but sort everything else, you should select 3 as Offset, check the "reverse" box, and check the "Limit Before Sorting" box.'),
      ],
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->getSetting('limit')['number'] == '' ? $this->t('Show all') : $this->t('Show @item', ['@item' => $this->getSetting('limit')['number']]);
    $summary[] = $this->getSetting('limit')['offset'] == 0 ? $this->t('No offset') : $this->t('Offset @item', ['@item' => $this->getSetting('limit')['offset']]);
    $summary[] = $this->getSetting('limit')['reverse'] ? $this->t('Limit from end of list') : $this->t('Limit from start of list.');
    $summary[] = $this->t('Sort by: @sort_field , @asc', [
      '@sort_field' => $this->getSetting('sort')['field'] ? $this->getSetting('sort')['field'] : 'delta',
      '@asc' => $this->getSetting('sort')['asc'] ? 'ASC' : 'DESC',
    ]);
    $summary[] = $this->getSetting('limit')['limit_before_sort'] ? $this->t('Apply limits before sorting.') : $this->t('Apply limits after sorting');
    if ($this->getPluginId() == 'entity_reference_entity_view_delta' && $this->getSetting('display')['enable']) {
      $summary[] = $this->formatPlural($this->getSetting('display')['number'], 'Show first entity in view mode @mode', 'Show first @number entities in view mode @mode', ['@number' => $this->getSetting('display')['number'], '@mode' => $this->getSetting('display')['view_mode']]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntitiesToView(EntityReferenceFieldItemListInterface $items, $langcode) {
    $entities = parent::getEntitiesToView($items, $langcode);
    if ($this->getSetting('limit')['limit_before_sort']) {
      $entities = $this->deltaFilter($entities);
    }
    if ($this->getSetting('sort')['field']) {
      uasort($entities, [$this, 'entitySort']);
    }
    elseif (!$this->getSetting('sort')['asc']) {
      // In this scenario we are sorting backwards by delta.
      $entities = array_reverse($entities, TRUE);
    }
    if (!$this->getSetting('limit')['limit_before_sort']) {
      $entities = $this->deltaFilter($entities);
    }
    return $entities;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    if ($this->getPluginId() == 'entity_reference_entity_view_delta' && $this->getSetting('display')['enable']) {
      $entity = $items[0]->getEntity()->{$items[0]->getFieldDefinition()->getName()}->entity;
      if ($entity instanceof EntityInterface) {
        $elements = $this->different_display($elements, $entity->getEntityTypeId());
      }
    }
    $elements['#sorted'] = TRUE;
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    // There's no reason to sort a field that can only have one value!
    return parent::isApplicable($field_definition) && $field_definition->getFieldStorageDefinition()->getCardinality() !== 1;
  }

  /**
   * Limit the entities that are displayed.
   *
   * @param array $entities
   *   Array of referenced entities.
   *
   * @return array
   *   Filtered referenced entities.
   */
  private function deltaFilter(array $entities) {
    $offset = $this->getSetting('limit')['offset'] ? $this->getSetting('limit')['offset'] : 0;
    $reverse = $this->getSetting('limit')['reverse'];
    $number = $this->getSetting('limit')['number'] ? $this->getSetting('limit')['number'] : NULL;
    if (!$reverse) {
      return array_slice($entities, $offset, $number, TRUE);
    }
    else {
      $reversed = array_reverse($entities, TRUE);
      $limited_reversed = array_slice($reversed, $offset, $number, TRUE);
      return array_reverse($limited_reversed, TRUE);
    }
  }

  /**
   * Provide the setting form element for limiting of filtered items.
   *
   * @return array
   *   form element
   */
  public function limitSetting() {
    $setting = [
      '#type' => 'fieldset',
      '#title' => $this->t('Limit Configuration'),
      '#tree' => TRUE,
    ];
    $options = [];
    for ($i = 1; $i < $this->getCardinalityCounter(); $i++) {
      $options[$i] = $i;
    }
    $setting['number'] = [
      '#type' => 'select',
      '#title' => $this->t('Items to Show'),
      '#options' => $options,
      '#empty_option' => $this->t('All'),
      '#default_value' => $this->getSetting('limit')['number'],
    ];
    $setting['offset'] = [
      '#type' => 'select',
      '#title' => $this->t('Offset'),
      '#options' => $options,
      '#empty_option' => 0,
      '#default_value' => $this->getSetting('limit')['offset'],
    ];
    $setting['reverse'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Reverse'),
      '#default_value' => $this->getSetting('limit')['reverse'],
      '#required' => FALSE,
      '#description' => $this->t('If checked, the limiting will begin from the end of the list. The order of the elements will not be affected.'),
    ];
    $setting['limit_before_sort'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Limit Before Sorting'),
      '#default_value' => $this->getSetting('limit')['limit_before_sort'],
      '#required' => FALSE,
    ];
    return $setting;
  }

  /**
   * Provide the setting form element for the sort settings.
   *
   * @return array
   *   form element
   */
  public function sortSetting() {
    $setting = [
      '#type' => 'fieldset',
      '#title' => $this->t('Sorting Configuration'),
      '#tree' => TRUE,
    ];
    $setting['field'] = [
      '#type' => 'select',
      '#options' => self::getFieldOptions($this->fieldDefinition),
      '#empty_option' => $this->t('delta (i.e. field order)'),
      '#title' => $this->t('Sort by'),
      '#default_value' => $this->getSetting('sort')['field'],
      '#required' => FALSE,
    ];
    $setting['asc'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Sort Ascending'),
      '#default_value' => $this->getSetting('sort')['asc'],
      '#required' => FALSE,
    ];
    $setting['details'] = [
      '#type' => 'details',
      '#title' => $this->t('Notes'),
      0 => [
        '#plain_text' => $this->t('Ties are always broken using delta. If two items are tied, the one with the lower delta will come first whether sorting ASC or DESC. Items lacking a value for the field being used for sorting will be placed at the end of the list whether sorting ASC or DESC.'),
      ],
    ];
    return $setting;
  }

  /**
   * Get the cardinality counter for select options.
   *
   * Get cardinality of the field or for unlimited cardinality
   * get a configured max value used for select options.
   *
   * @return int
   *   cardinality counter number
   */
  public function getCardinalityCounter() {
    if ($this->fieldDefinition->getFieldStorageDefinition()->getCardinality() == -1) {
      return \Drupal::config('entityreference_extensions.settings')->get('unlimitedcounter') ? \Drupal::config('entityreference_extensions.settings')->get('unlimitedcounter') : 10;
    }
    else {
      return $this->fieldDefinition->getFieldStorageDefinition()->getCardinality();
    }
  }

  /**
   * The function used for sorting entities.
   *
   * @param \Drupal\Core\Entity\EntityInterface $a
   *   The first entity being considered in sort.
   * @param \Drupal\Core\Entity\EntityInterface $b
   *   The second entity being considered.
   *
   * @return int
   *   Int indicating sort order.
   *
   * @see https://www.php.net/manual/en/function.uasort.php
   */
  public function entitySort(EntityInterface $a, EntityInterface $b) {
    $sort_asc = $this->getSetting('sort')['asc'] ? 1 : -1;
    $sort_field = $this->getSetting('sort')['field'];
    $values = [NULL, NULL];
    $entities = [$a, $b];
    foreach ($entities as $index => $entity) {
      if ($entity->hasField($sort_field)) {
        $field_item = $entity->{$sort_field}->first();
        if (!empty($field_item)) {
          $field_value = $field_item->getValue();
          $values[$index] = !empty($field_value) ? $field_value[$field_item->mainPropertyName()] : NULL;
        }
      }
    }
    $val_a = $values[0];
    $val_b = $values[1];
    if (isset($val_a) && isset($val_b)) {
      if ($val_a == $val_b) {
        return 0;
      }
      return ($val_a < $val_b) ? -$sort_asc : $sort_asc;
    }
    // Down here? At least one value doesn't exist.
    // Values that exist are placed nearer the top of the list
    // whether ASC or DESC.
    if (isset($val_a)) {
      return -1;
    }
    elseif (isset($val_b)) {
      return 1;
    }
    else {
      // Neither value exists.
      return 0;
    }
  }

  /**
   * Based on allowed bundles, get field options for sorting.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   Field definition for which to find fields that can be used for sorting.
   *
   * @return array
   *   Field options that can be used for sorting.
   */
  public static function getFieldOptions(FieldDefinitionInterface $field_definition) {
    $referenced_entity_type = $field_definition->getFieldStorageDefinition()->getSetting('target_type');
    $bundles = isset($field_definition->getSettings()['handler_settings']['target_bundles']) ? $field_definition->getSettings()['handler_settings']['target_bundles'] : NULL;
    $fields = [];
    $entity_field_manager = \Drupal::service('entity_field.manager');
    if ($bundles) {
      foreach ($bundles as $bundle => $label) {
        $fields = array_merge($fields, $entity_field_manager->getFieldDefinitions($referenced_entity_type, $bundle));
      }
    }
    else {
      $fields = $entity_field_manager->getFieldDefinitions($referenced_entity_type, NULL);
    }
    $field_options = [];
    foreach ($fields as $key => $field) {
      $field_options[$key] = $key;
    }
    // Sort the list alphabetically to be nice.
    asort($field_options);
    return $field_options;
  }

}
