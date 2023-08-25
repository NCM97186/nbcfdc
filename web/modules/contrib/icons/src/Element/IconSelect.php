<?php

namespace Drupal\icons\Element;

use Drupal\Core\Form\OptGroup;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Annotation\FormElement;
use Drupal\Core\Render\Element\Select;

/**
 * Provides a form element for a drop-down menu to select an icon.
 *
 * Usage example:
 *
 * @code
 * $form['icon_select'] = [
 *   '#type' => 'icon_select',
 *   '#title' => $this->t('Select element'),
 *   '#options' => [
 *     '1' => $this->t('One'),
 *     '2' => [
 *       '2.1' => $this->t('Two point one'),
 *       '2.2' => $this->t('Two point two'),
 *     ],
 *     '3' => $this->t('Three'),
 *   ],
 * ];
 * @endcode
 *
 * @FormElement("icon_select")
 */
class IconSelect extends Select {

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    $class = get_class($this);
    $info = parent::getInfo();
    $info['#theme_wrappers'][] = 'icon_select';
    $info['#process'][] = [$class, 'processIconSelect'];

    return $info;
  }

  /**
   * Processes a icon select list form element.
   *
   * @param array $element
   *   The form element to process.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param array $complete_form
   *   The complete form structure.
   *
   * @return array
   *   The processed element.
   */
  public static function processIconSelect(array &$element, FormStateInterface $form_state, array &$complete_form): array {
    $items = [];

    $element['#attached']['library'][] = 'icons/icon_picker';
    $element['#icon_picker'] = [
      '#theme' => 'item_list',
      '#type' => 'ul',
      '#empty_value' => '',
      '#attributes' => $element['#attributes'],
    ];

    foreach ($element['#options'] as $key => $data) {
      if (!is_array($data)) {
        $icon_id = $key;
        $icon_label = $data;
        $items[] = self::buildListItem($element, $icon_id, $icon_label);
        continue;
      }

      $items[$key] = [
        '#markup' => $key,
        '#wrapper_attributes' => [
          'class' => [
            'icon-set',
          ],
        ],
        'children' => [],
      ];

      foreach ($data as $icon_id => $icon_label) {
        $items[$key]['children'][] = self::buildListItem($element, $icon_id, $icon_label);
      }
    }

    $element['#icon_picker']['#items'] = $items;
    return $element;
  }

  /**
   * Build a list item for the custom dropdown to pick an icon.
   *
   * @param array $element
   *   The form element to process.
   * @param string $icon_id
   *   Id of the icon to build a list item for.
   * @param string $icon_label
   *   Label which represents the textual representation of the icon.
   *
   * @return array
   *   List item to add to the item list.
   */
  protected static function buildListItem(array $element, string $icon_id, string $icon_label): array {
    $value = explode(':', $icon_id);
    if (count($value) <= 1) {
      return [
        [
          'icon_option' => [
            'label' => [
              '#prefix' => '<span class="icons-select__label">',
              '#suffix' => '</span>',
              '#type' => 'markup',
              '#markup' => $icon_label,
            ],
            '#wrapper_attributes' => [
              'data-icon-id' => $icon_id,
              'class' => [
                'icons-select__item',
              ],
            ],
          ],
        ],
      ];
    }

    [$icon_set, $icon_name] = $value;
    $option = [
      'icon' => [
        '#type' => 'icon',
        '#icon_set' => $icon_set,
        '#icon_name' => $icon_name,
        '#title' => $icon_label,
      ],
      'label' => [
        '#prefix' => '<span class="icons-select__label">',
        '#suffix' => '</span>',
        '#type' => 'markup',
        '#markup' => $icon_label,
      ],
    ];

    $item = [
      'icon_option' => $option,
      '#wrapper_attributes' => [
        'data-icon-id' => $icon_id,
        'class' => [
          'icons-select__item',
        ],
      ],
    ];

    if (empty($element['#default_value'])) {
      return $item;
    }

    if ((!is_array($element['#default_value']) && $element['#default_value'] === $icon_id) || (is_array($element['#default_value']) && $element['#default_value'][0] === $icon_id)) {
      $item['#wrapper_attributes']['class'][] = 'selected';
    }

    return $item;
  }

}
