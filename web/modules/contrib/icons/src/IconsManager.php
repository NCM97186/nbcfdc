<?php

namespace Drupal\icons;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Render\RendererInterface;

/**
 * Class IconsManager
 *
 * @package Drupal\icons
 */
class IconsManager {

  /**
   * Entity Type Manager
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Menu Link Content Storage
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $menuLinkContentStorage;

  /**
   * Renderer
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * IconsManager constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, RendererInterface $renderer) {
    $this->entityTypeManager = $entityTypeManager;
    $this->renderer = $renderer;
    $this->menuLinkContentStorage = $this->entityTypeManager->getStorage('menu_link_content');
  }

  /**
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getIconOptions(): array {
    $options = [];

    $iconSetStorage = $this->entityTypeManager->getStorage('icon_set');

    /** @var \Drupal\icons\Entity\IconSetInterface[] $icon_sets */
    $icon_sets = $iconSetStorage->loadMultiple();

    $use_groups = FALSE;
    if (count($icon_sets) > 1) {
      $use_groups = TRUE;
    }

    // Add a library whose information changes depending on certain conditions.
    foreach ($icon_sets as $icon_set) {
      /** @var \Drupal\icons_icomoon\Plugin\IconLibrary\IcoMoon $plugin */
      $plugin = $icon_set->getPlugin();
      $icons = $plugin->getIcons();

      $icon_values = ['' => t('None')];
      foreach ($icons as $icon_key => $icon_title) {
        $icon_values[$icon_set->id() . ':' . $icon_key] = $icon_title;
      }

      if ($use_groups) {
        $options[$icon_set->label()] = $icon_values;
      }
      else {
        $options = $icon_values;
      }
    }

    return $options;
  }

  /**
   * Process the menu items.
   *
   * @param array $items
   *
   * @return array
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public function processMenuItems(array &$items): array {
    foreach ($items as &$item) {
      $menu_link_icons = $this->getMenuItemIcons($item['original_link']);

      if (!empty($menu_link_icons)) {
        $item = $this->formatMenuIconItem($item, $menu_link_icons);
      }

      if (!empty($item['below'])) {
        $this->processMenuItems($item['below']);
      }
    }

    return $items;
  }

  /**
   * Get menu link attributes.
   *
   * @param \Drupal\Core\Menu\MenuLinkInterface $menu_link_plugin
   *   A menu link content plugin.
   *
   * @return array
   *   An array with the attributes.
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public function getMenuItemIcons(MenuLinkInterface $menu_link_plugin): array {
    $icons = [];

    $plugin_id = $menu_link_plugin->getPluginId();
    if (strpos($plugin_id, ':') === FALSE) {
      return $icons;
    }

    [$entity_type, $uuid] = explode(':', $plugin_id, 2);

    switch ($entity_type) {
      case 'menu_link_content':
        try {
          $uuidKey = $this->menuLinkContentStorage->getEntityType()
            ->getKey('uuid');
          /** @var \Drupal\menu_link_content\MenuLinkContentInterface[] $entities */
          $entities = $this->menuLinkContentStorage->loadByProperties([$uuidKey => $uuid]);

          if (empty($entities)) {
            return $icons;
          }

          $entity = ($entities) ? reset($entities) : NULL;

          /** @var \Drupal\link\LinkItemInterface $menuLinkItem */
          $menuLinkItem = $entity->get('link')->first();
          $options = $menuLinkItem->get('options')->getValue();

          if (isset($options['icons'])) {
            $icons = $options['icons'];
          }
        } catch (PluginNotFoundException $e) {
          // Make sure we catch failed entity loadings.
        }
        break;

      case 'views_view':
        /** @var \Drupal\views\ViewExecutable $view */
        $view = $menu_link_plugin->loadView();
        $display = $view->getDisplay();
        $options = $display->getOption('menu');

        if (!empty($options['icon_suffix'])) {
          $icons['icon_suffix'] = $options['icon_suffix'];
        }
        if (!empty($options['icon_prefix'])) {
          $icons['icon_prefix'] = $options['icon_prefix'];
        }
    }

    return $icons;
  }

  /**
   * Format a menu item with icons.
   *
   * @param array $item
   * @param array $icons
   *
   * @return array
   * @throws \Exception
   */
  public function formatMenuIconItem(array $item, array $icons): array {
    if (empty($icons)) {
      return $item;
    }

    $title = [];
    if (!isset($item['attributes']['title'])) {
      $item['attributes']['title'] = $item['title'];
    }

    // Build the render array for the title which also includes the icon prefix
    // and suffix now.
    if (!empty($icons['icon_prefix'])) {
      $value = explode(':', $icons['icon_prefix']);
      if (count($value) > 1) {
        [$icon_set, $icon_name] = $value;
        $title['icon_prefix'] = [
          '#type' => 'icon',
          '#icon_set' => $icon_set,
          '#icon_name' => $icon_name,
          '#title' => $item['title'],
        ];
      }
    }

    $title['title'] = [
      '#type' => 'markup',
      '#markup' => '<span class="menu-icon-label">' . $item['title'] . '</span>',
    ];

    if (!empty($icons['icon_suffix'])) {
      $value = explode(':', $icons['icon_suffix']);
      if (count($value) > 1) {
        [$icon_set, $icon_name] = $value;
        $title['icon_suffix'] = [
          '#type' => 'icon',
          '#icon_set' => $icon_set,
          '#icon_name' => $icon_name,
          '#title' => $item['title'],
        ];
      }
    }

    $item['title'] = $this->renderer->render($title);

    return $item;
  }

}
