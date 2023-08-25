<?php

namespace Drupal\icons_icomoon\Plugin\IconLibrary;

use Drupal;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\icons\Annotation\IconLibrary;
use Drupal\icons\IconLibraryPluginJsonBase;
use Drupal\Component\Serialization\Json;

/**
 * Defines a icon library plugin to integrate Icomoon with icons module.
 *
 * @IconLibrary(
 *   id = "icomoon",
 *   label = @Translation("Icomoon"),
 *   description = @Translation("Integration with Icomoon for the icons module."),
 * )
 */
class IcoMoon extends IconLibraryPluginJsonBase {

  /**
   * {@inheritdoc}
   */
  public function build(array $element, ConfigEntityInterface $entity, $name): array {
    $prefix = $this->configuration['prefix'];
    $element['#attributes']['class'][] = $prefix . $name;
    $element['#attached']['library'][] = 'icons_icomoon/' . $entity->id();
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'library_path' => '',
      'name' => 'icomoon',
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
    /** @var Drupal\Core\File\FileSystem $fileSystem */
    $fileSystem = Drupal::service('file_system');
    $path = $fileSystem->realpath($library_path);
    if ($path) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Validate that the selection.json file exists in the given library path.
   *
   * @param string $library_path
   *   Path to the library folder of the icomoon files.
   *
   * @return bool
   *   Indicating if the path validates.
   */
  public function validateLibraryJson(string $library_path): bool {
    $json_uri = $library_path . '/selection.json';
    $path = Drupal::service('file_system')->realpath($json_uri);

    if ($path) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Validate the existence of the style css file in the given library path.
   *
   * @param string $library_path
   *   Path to the library folder of the icomoon files.
   *
   * @return bool
   *   Indicating if the path validates.
   */
  public function validateLibraryCss(string $library_path): bool {
    $style_uri = $library_path . '/style.css';
    $path = Drupal::service('file_system')->realpath($style_uri);

    if ($path) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Process information from selection.json into the configuration settings.
   */
  public function processJson(): void {
    $json_uri = $this->getLibraryRealPath() . '/selection.json';
    $path = Drupal::service('file_system')->realpath($json_uri);

    $name = 'icomoon';
    $icons = [];
    $prefix = 'icon-';

    if ($path) {
      $json_string = file_get_contents($path);
      $config = Json::decode($json_string);

      foreach ($config['icons'] as $icon) {
        $icon_name = $icon['properties']['name'];
        $icon_title = $icon['icon']['tags'][0];
        $icons[$icon_name] = $icon_title;
      }

      $name = $config['metadata']['name'];
      $prefix = $config['preferences']['fontPref']['prefix'];
    }

    $this->configuration['name'] = $name;
    $this->configuration['prefix'] = $prefix;
    $this->configuration['icons'] = $icons;
  }

}
