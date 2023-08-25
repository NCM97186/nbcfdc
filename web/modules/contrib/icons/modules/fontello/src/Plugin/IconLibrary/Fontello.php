<?php

namespace Drupal\icons_fontello\Plugin\IconLibrary;

use Drupal;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\icons\Annotation\IconLibrary;
use Drupal\icons\IconLibraryPluginJsonBase;
use Drupal\Component\Serialization\Json;

/**
 * Defines a icon library plugin to integrate Fontello with icons module.
 *
 * @IconLibrary(
 *   id = "fontello",
 *   label = @Translation("Fontello"),
 *   description = @Translation("Integration with Fontello for the icons module."),
 * )
 */
class Fontello extends IconLibraryPluginJsonBase {

  /**
   * {@inheritdoc}
   */
  public function build(array $element, ConfigEntityInterface $entity, $name): array {
    $prefix = $this->configuration['prefix'];
    $element['#attributes']['class'][] = $prefix . $name;
    $element['#attached']['library'][] = 'icons_fontello/' . $entity->id();
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'library_path' => '',
      'name' => 'fontello',
      'prefix' => 'icon-',
      'suffix' => FALSE,
      'icons' => [],
    ];
  }

  /**
   * Validate the existence of the file folder based on the given library path.
   *
   * @param string $library_path
   *   Path to the library folder of the icomoon files.
   *
   * @return bool
   *   Indicating if the path validates.
   */
  public function validateLibraryPath(string $library_path): bool {
    $path = Drupal::service('file_system')->realpath($library_path);
    if ($path) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Validate the existence of the config.json file in the given library path.
   *
   * @param string $library_path
   *   Path to the library folder of the icomoon files.
   *
   * @return bool
   *   Indicating if the path validates.
   */
  public function validateLibraryJson(string $library_path): bool {
    $json_uri = $library_path . '/config.json';
    $path = Drupal::service('file_system')->realpath($json_uri);

    if ($path) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Validate the existence of the fontello css file in the given library path.
   *
   * @param string $library_path
   *   Path to the library folder of the icomoon files.
   *
   * @return bool
   *   Indicating if the path validates.
   */
  public function validateLibraryCss(string $library_path): bool {
    $style_uri = $library_path . '/css/fontello.css';
    $path = Drupal::service('file_system')->realpath($style_uri);

    if ($path) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getIcons(): array {
    $icons = array_keys($this->configuration['icons']);
    return array_combine($icons, $icons);
  }

  /**
   * Process information from config.json into the configuration settings.
   */
  public function processJson(): void {
    $json_uri = $this->getLibraryRealPath() . '/config.json';
    $path = Drupal::service('file_system')->realpath($json_uri);

    $name = 'fontello';
    $icons = [];
    $prefix = 'icon-';
    $suffix = FALSE;

    if ($path) {
      $json_string = file_get_contents($path);
      $config = Json::decode($json_string);

      foreach ($config['glyphs'] as $icon) {
        $icon_name = $icon['css'];
        $icon_source = $icon['src'];
        $icons[$icon_name] = [
          'name' => $icon_name,
          'src' => $icon_source,
        ];
      }

      if (!empty($config['name'])) {
        $name = $config['name'];
      }
      $prefix = $config['css_prefix_text'];
      $suffix = $config['css_use_suffix'];
    }

    $this->configuration['name'] = $name;
    $this->configuration['prefix'] = $prefix;
    $this->configuration['suffix'] = $suffix;
    $this->configuration['icons'] = $icons;
  }

}
