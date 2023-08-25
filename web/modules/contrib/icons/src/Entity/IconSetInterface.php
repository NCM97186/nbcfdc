<?php

namespace Drupal\icons\Entity;

use Drupal\Component\Plugin\LazyPluginCollection;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\icons\IconLibraryPluginCollection;
use Drupal\icons\IconLibraryPluginInterface;

/**
 * Provides an interface for defining Icon Set entities.
 */
interface IconSetInterface extends ConfigEntityInterface {

  /**
   * Returns the plugin instance.
   *
   * @return \Drupal\icons\IconLibraryPluginInterface|null
   *   The plugin instance for this icon set.
   */
  public function getPlugin(): ?IconLibraryPluginInterface;

  /**
   * Encapsulates the creation of the icon library LazyPluginCollection.
   *
   * @return \Drupal\icons\IconLibraryPluginCollection The icon library plugin collection.
   *   The icon library plugin collection.
   */
  public function getPluginCollection(): IconLibraryPluginCollection;

  /**
   * Returns the plugin ID.
   *
   * @return string
   *   The plugin ID for this icon provider.
   */
  public function getPluginId(): string;

}
