<?php

namespace Drupal\entityreference_extensions\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceEntityFormatter;
use Drupal\entityreference_extensions\EntityReferenceDeltaFilterTrait;

/**
 * Plugin implementation of 'entity reference rendered entity delta' formatter.
 *
 * @FieldFormatter(
 *   id = "entity_reference_entity_view_delta",
 *   label = @Translation("Rendered entity (PLUS)"),
 *   description = @Translation("Display the referenced entities rendered by entity_view(). With more options (delta, sorting)."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class EntityReferenceEntityDeltaFormatter extends EntityReferenceEntityFormatter {

  use EntityReferenceDeltaFilterTrait;

  /**
   * Provide the setting form element for limiting of filtered items.
   *
   * @return array
   *   form element
   */
  public function displaySetting() {
    $setting = [
      '#type' => 'fieldset',
      '#title' => $this->t('Different Display Configuration'),
      '#tree' => TRUE
    ];

    $options = [];
    for ($i = 1; $i < $this->getCardinalityCounter(); $i++) {
      $options[$i] = $i;
    }
    $setting['enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display some entities in different view mode'),
      '#default_value' => $this->getSetting('display')['enable'],
      '#description' => $this->t('This is done after sorting and limiting')
    ];
    $setting['number'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of entities to display in different view mode'),
      '#options' => $options,
      '#default_value' => $this->getSetting('display')['number'],
      '#description' => $this->t('the number of entities beginning with the first one')
    ];
    $setting['view_mode'] = [
      '#type' => 'select',
      '#options' => $this->entityDisplayRepository->getViewModeOptions($this->getFieldSetting('target_type')),
      '#title' => t('View mode'),
      '#default_value' => $this->getSetting('display')['view_mode']
    ];
    return $setting;
  }

  /**
   * change the view mode of some entities according to setting
   *
   * @param $elements
   * @param $entity_type_id
   *
   * @return mixed
   */
  public function different_display($elements, $entity_type_id) {
    $view_mode = $this->getSetting('display')['view_mode'];
    $number_of_items = count($elements) < $this->getSetting('display')['number'] ? count($elements) : $this->getSetting('display')['number'];
    $view_builder = $this->entityTypeManager->getViewBuilder($entity_type_id);
    $different_elements = array_slice($elements, 0, $number_of_items, TRUE);
    foreach ($different_elements as $key => $different_element) {
      /* @var \Drupal\Core\Entity\EntityInterface $entity */
      $entity = $different_element['#'.$entity_type_id];
      $elements[$key] = $view_builder->view($entity, $view_mode, $entity->language()->getId());
    }
    return $elements;
  }

}
