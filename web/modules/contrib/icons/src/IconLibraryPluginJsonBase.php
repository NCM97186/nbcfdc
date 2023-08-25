<?php

namespace Drupal\icons;

use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a base icon set implementation that most icon library plugins extend.
 *
 * This abstract class provides the generic icon provider configuration form,
 * default icon provider settings, and handling for general user-defined icon
 * provider visibility settings.
 *
 * @ingroup icons
 */
abstract class IconLibraryPluginJsonBase extends IconLibraryPluginBase {

  /**
   * {@inheritdoc}
   */
  public function iconLibraryForm(array $form, FormStateInterface $form_state): array {
    return [
      'library_path' => [
        '#title' => $this->t('Library Path'),
        '#type' => 'textfield',
        '#default_value' => $this->configuration['library_path'],
        '#description' => $this->t("Library path."),
        '#required' => TRUE,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function iconLibraryValidate(array &$form, FormStateInterface $form_state): void {
    $library_path = DRUPAL_ROOT . '/' . $form_state->getValue('library_path');

    // Validate the folder.
    if (!$this->validateLibraryPath($library_path)) {
      $form_state->setErrorByName('library_path', $this->t('Given library path does not exist'));
    }

    // Validate the selection.json.
    if (!$this->validateLibraryJson($library_path)) {
      $form_state->setErrorByName('library_path', $this->t('Given library path does not contain a valid json file'));
    }

    // Validate the css file.
    if (!$this->validateLibraryCss($library_path)) {
      $form_state->setErrorByName('library_path', $this->t('Given library path does not contain a valid css file'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function iconLibrarySubmit(array &$form, FormStateInterface $form_state): void {
    $this->configuration['library_path'] = $form_state->getValue('library_path');
    $this->processJson();
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
  abstract protected function validateLibraryPath(string $library_path): bool;

  /**
   * Validate that the selection.json file exists in the given library path.
   *
   * @param string $library_path
   *   Path to the library folder of the icomoon files.
   *
   * @return bool
   *   Indicating if the path validates.
   */
  abstract protected function validateLibraryJson(string $library_path): bool;

  /**
   * Validate the existence of the style css file in the given library path.
   *
   * @param string $library_path
   *   Path to the library folder of the icomoon files.
   *
   * @return bool
   *   Indicating if the path validates.
   */
  abstract protected function validateLibraryCss(string $library_path): bool;

  /**
   * Process information from selection.json into the configuration settings.
   */
  abstract protected function processJson(): void;

  /**
   * Get library path from the configuration.
   *
   * @return string
   *   Library path.
   */
  public function getLibraryPath(): string {
    return $this->configuration['library_path'];
  }

  /**
   * Get the public path of the file.
   *
   * @return string
   */
  public function getLibraryPublicPath(): string {
    return '/' . $this->getLibraryPath();
  }

  /**
   * Get library base path for given path.
   *
   * @return string
   *   Library base path.
   */
  public function getLibraryRealPath(): string {
    return DRUPAL_ROOT . '/' . $this->getLibraryPublicPath();
  }
}
