<?php

namespace Drupal\languages_dropdown\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\language\Plugin\Block\LanguageBlock;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Theme\ThemeManager;
use Drupal\Core\Path\PathMatcherInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Languages Dropdown (Bootstrap)' block.
 *
 * @Block(
 *  id = "lang_drop_bootstrap",
 *  admin_label = @Translation("Languages dropdown (Bootstrap)"),
 *  deriver = "Drupal\languages_dropdown\Plugin\Derivative\LangDropBootstrapDeriver"
 * )
 */
class LangDropBootstrapBlock extends LanguageBlock {

  /**
   * The theme manager.
   *
   * @var \Drupal\Core\Theme\ThemeManager
   */
  protected $themeManager;

  /**
   * Constructs an LanguageBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Path\PathMatcherInterface $path_matcher
   *   The path matcher.
   * @param \Drupal\Core\Theme\ThemeManager $theme_manager
   *   The theme manager.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    LanguageManagerInterface $language_manager,
    PathMatcherInterface $path_matcher,
    ThemeManager $theme_manager) {

    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $language_manager,
      $path_matcher
    );
    $this->themeManager = $theme_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition) {

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('language_manager'),
      $container->get('path.matcher'),
      $container->get('theme.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();
    $settings = $config['languages_dropdown_bootstrap'];

    $form['languages_dropdown_bootstrap'] = [
      '#type' => 'details',
      '#title' => $this->t('Bootstrap settings'),
      '#open' => TRUE,
    ];

    $form['languages_dropdown_bootstrap']['components'] = [
      '#type' => 'select',
      '#title' => $this->t('Dropdown display components'),
      '#options' => [
        'all' => $this->t('Icons and text'),
        'icons' => $this->t('Only icons'),
      ],
      '#default_value' => !empty($settings['components']) ?
      $settings['components'] :
      'all',
    ];

    $form['languages_dropdown_bootstrap']['size'] = [
      '#type' => 'select',
      '#title' => $this->t('Icon Size'),
      '#options' => [
        'xs' => $this->t('Small'),
        'sm' => $this->t('Medium'),
        'lg' => $this->t('Large'),
      ],
      '#default_value' => !empty($settings['size']) ?
      $settings['size'] :
      'xs',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();

    $this->configuration['languages_dropdown_bootstrap'] =
      $values['languages_dropdown_bootstrap'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $settings = $this->configuration['languages_dropdown_bootstrap'];
    $current_language = $this->languageManager
      ->getCurrentLanguage()
      ->getId();

    $route_name = $this->pathMatcher->isFrontPage() ? '<front>' : '<current>';
    $type = $this->getDerivativeId();
    $links = $this->languageManager->getLanguageSwitchLinks(
      $type,
      Url::fromRoute($route_name)
    );

    if (isset($links->links)) {
      $theme_name = $this->themeManager->getActiveTheme()->getName();
      $base_themes = array_keys(
        $this->themeManager->getActiveTheme()->getBaseThemeExtensions()
      );

      $is_bootstrap = (
        $theme_name === 'bootstrap' ||
        in_array('bootstrap', $base_themes)
      ) ? TRUE : FALSE;

      if ($is_bootstrap) {
        $build = [
          '#theme' => 'links__lang_drop_bootstrap_block',
          '#links' => $links->links,
          '#icon' => $settings['size'],
          '#icon_only' => ($settings['components'] === 'icons') ? TRUE : FALSE,
        ];
      }
      else {
        $languages = [];
        foreach ($links->links as $key => $link) {
          /** @var \Drupal\Core\Url $url */
          $url = $link['url'];
          unset($link['url']);

          if ($url instanceof Url) {
            $url_options = NestedArray::mergeDeep(
              $url->getOptions(),
              $link
            );

            $languages[$key] = $url->setOptions($url_options);
          }
        }

        $build = [
          '#theme' => 'lang_drop_bootstrap_block',
          '#languages' => $languages,
          '#size' => $settings['size'],
          '#icon_only' => ($settings['components'] === 'icons') ? TRUE : FALSE,
          '#current_language' => $current_language,
          '#set_active_class' => TRUE,
        ];
      }

      $build['#set_active_class'] = TRUE;
      $build['#attached'] = [
        'library' => [
          'languages_dropdown/main',
        ],
      ];
    }

    return $build;
  }

}
