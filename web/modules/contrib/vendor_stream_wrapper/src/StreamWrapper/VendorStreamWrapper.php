<?php

namespace Drupal\vendor_stream_wrapper\StreamWrapper;

use Drupal\Core\Site\Settings;
use Drupal\Core\StreamWrapper\LocalReadOnlyStream;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\vendor_stream_wrapper\Exception\VendorDirectoryNotFoundException;

/**
 * Creates a vendor:// stream wrapper, for files in the vendor folder.
 */
class VendorStreamWrapper extends LocalReadOnlyStream implements VendorStreamWrapperInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->t('Vendor Files');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Vendor local files served by Drupal.');
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl() {
    $path = str_replace('\\', '/', $this->getTarget());
    return Url::fromRoute('vendor_stream_wrapper.vendor_file_download', ['filepath' => $path], ['path_processing' => FALSE])->toString();
  }

  /**
   * {@inheritdoc}
   */
  public function getDirectoryPath(): string {
    return static::basePath();
  }

  /**
   * {@inheritdoc}
   */
  public static function basePath(): string {
    if ($custom_path = Settings::get('vendor_file_path')) {
      return $custom_path;
    }

    if (is_dir('../vendor')) {
      return '../vendor';
    }

    if (is_dir('vendor')) {
      return 'vendor';
    }

    throw new VendorDirectoryNotFoundException();
  }

}
