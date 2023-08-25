<?php

namespace Drupal\icons_test_config\Plugin\IconLibrary;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\icons\Annotation\IconLibrary;
use Drupal\icons\IconLibraryPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a test icon library plugin for testing icons module.
 *
 * @IconLibrary(
 *   id = "icontest",
 *   label = @Translation("Icontest"),
 *   description = @Translation("Some description about what this plugin does and notes about configuration."),
 * )
 */
class IconTest extends IconLibraryPluginBase {

  /**
   * {@inheritdoc}
   */
  public function build(array $element, ConfigEntityInterface $entity, $name): array {
    $prefix = $this->configuration['prefix'];
    $element['#attributes']['class'][] = $prefix . $name;
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function iconLibraryForm(array $form, FormStateInterface $form_state): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function iconLibrarySubmit(array &$form, FormStateInterface $form_state): void {
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'name' => 'icontest',
      'prefix' => 'icon-',
      'icons' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIcons(): array {
    return $this->configuration['icons'];
  }

}
