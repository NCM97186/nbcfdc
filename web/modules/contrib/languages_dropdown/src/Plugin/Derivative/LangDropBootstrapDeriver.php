<?php

namespace Drupal\languages_dropdown\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Language\LanguageManagerInterface;

/**
 * Provides language switcher block plugin definitions for languages.
 */
class LangDropBootstrapDeriver extends DeriverBase implements
    ContainerDeriverInterface {

  use StringTranslationTrait;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Constructs an LangDropBootstrapDeriver object.
   *
   * @param string $base_plugin_id
   *   The base plugin ID.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   */
  public function __construct(
    $base_plugin_id,
    LanguageManagerInterface $language_manager) {

    $this->basePluginId = $base_plugin_id;
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    $base_plugin_id) {

    return new static(
      $base_plugin_id,
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $info = $this->languageManager->getDefinedLanguageTypesInfo();
    $configurable_types = $this->languageManager->getLanguageTypes();

    foreach ($configurable_types as $type) {
      $this->derivatives[$type] = $base_plugin_definition;
      $this->derivatives[$type]['admin_label'] = $this->t(
        'Language switcher (Bootstrap) - (@type)',
        ['@type' => $info[$type]['name']]
      );
    }

    // If there is just one configurable type then change the title of the
    // block.
    if (count($configurable_types) == 1) {
      $type = reset($configurable_types);
      $this->derivatives[$type]['admin_label'] = $this->t(
        'Language switcher (Bootstrap)'
      );
    }

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
