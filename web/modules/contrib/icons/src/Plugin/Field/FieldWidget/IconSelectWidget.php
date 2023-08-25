<?php

namespace Drupal\icons\Plugin\Field\FieldWidget;

use Drupal;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\Annotation\FieldWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\OptionsSelectWidget;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'icon_select_widget' widget.
 *
 * @FieldWidget(
 *   id = "icon_select_widget",
 *   label = @Translation("Icon select list"),
 *   field_types = {
 *     "list_icon"
 *   },
 *   multiple_values = TRUE
 * )
 */
class IconSelectWidget extends OptionsSelectWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $element['#type'] = 'icon_select';

    return $element;
  }

  /**
   * Get options list with allowed values for icons selection widget.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *
   * @return array
   *   Return list of allowed values to select in the icon select form element.
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getOptions(FieldableEntityInterface $entity): array {
    /** @var \Drupal\icons\IconsManager $iconsManager */
    $iconsManager = Drupal::service('icons.manager');
    return $iconsManager->getIconOptions();
  }
}
