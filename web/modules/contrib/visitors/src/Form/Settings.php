<?php

namespace Drupal\visitors\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ThemeExtensionList;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Visitors Settings Form.
 */
class Settings extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'visitors.config';


  /**
   * An extension discovery instance.
   *
   * @var \Drupal\Core\Extension\ThemeExtensionList
   */
  protected $themeList;

  /**
   * An extension discovery instance.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a Visitors Settings Form.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Extension\ThemeExtensionList $theme_list
   *   The theme extension list.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The enttyity_type.manager service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ThemeExtensionList $theme_list, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($config_factory);
    $this->themeList = $theme_list;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('extension.list.theme'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'visitors_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('visitors.config');
    $system_config = $this->config('system.theme');
    $form = parent::buildForm($form, $form_state);

    $roles = [];
    foreach ($this->entityTypeManager->getStorage('user_role')->loadMultiple() as $name => $role) {
      $roles[$name] = $role->label();
    }

    $form['excluded_roles'] = [
      '#type' => 'checkboxes',
      '#options' => $roles,
      '#title' => $this->t('Exclude users with roles from statistics'),
      '#default_value' => $config->get('excluded_roles'),
      '#description' => $this->t('Exclude hits of users with role(s) from statistics.'),
    ];

    $form['exclude_user1'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Exclude user1 from statistics'),
      '#default_value' => $config->get('exclude_user1'),
      '#description' => $this->t('Exclude hits of user1 from statistics.'),
    ];

    $all_themes = $this->themeList->getList();
    $default_theme = $system_config->get('default');
    $admin_theme = $system_config->get('admin');

    $default_name = $all_themes[$default_theme]->info['name'];
    $admin_name = $all_themes[$admin_theme]->info['name'];

    $themes_installed = [
      'admin' => $this->t('Admin (@admin)', ['@admin' => $admin_name]),
      'default' => $this->t('Default (@default)', ['@default' => $default_name]),
    ];

    $list_themes = array_filter($all_themes, function ($obj) {
      return $obj->status;
    });
    $themes_installed += array_map(function ($value) {
      return $value->info['name'];
    }, $list_themes);
    $form['theme'] = [
      '#type' => 'select',
      '#title' => $this->t('Set a theme for reports'),
      '#options' => $themes_installed,
      '#default_value' => $config->get('theme') ?: 'admin',
      '#description' => $this->t('Select a theme for the Visitors reports.'),
    ];

    $form['items_per_page'] = [
      '#type' => 'select',
      '#title' => 'Items per page',
      '#default_value' => $config->get('items_per_page'),
      '#options' => [
        5 => 5,
        10 => 10,
        25 => 25,
        50 => 50,
        100 => 100,
        200 => 200,
        250 => 250,
        500 => 500,
        1000 => 1000,
      ],
      '#description' =>
      $this->t('The default maximum number of items to display per page.'),
    ];

    $form['flush_log_timer'] = [
      '#type' => 'select',
      '#title' => $this->t('Discard visitors logs older than'),
      '#default_value'   => $config->get('flush_log_timer'),
      '#options' => [
        0 => 'Never',
        3600 => '1 hour',
        10800 => '3 hours',
        21600 => '6 hours',
        32400 => '9 hours',
        43200 => '12 hours',
        86400 => '1 day',
        172800 => '2 days',
        259200 => '3 days',
        604800 => '1 week',
        1209600 => '2 weeks',
        2419200 => '4 weeks',
        4838400 => '1 month 3 weeks',
        9676800 => '3 months 3 weeks',
        31536000 => '1 year',
      ],
      '#description' =>
      $this->t('Older visitors log entries (including referrer statistics) will be automatically discarded. (Requires a correctly configured <a href="@cron">cron maintenance task</a>.)',
          ['@cron' => Url::fromRoute('system.status')->toString()]
      ),
    ];

    $form['information'] = [
      '#type' => 'vertical_tabs',
      '#default_tab' => 'edit-publication',
    ];

    $form['block'] = [
      '#type' => 'details',
      '#title' => $this->t('Default Block'),
      '#description' => $this->t('Default block settings'),
      '#group' => 'information',
    ];

    $form['block']['show_total_visitors'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Total Visitors'),
      '#default_value' => $config->get('show_total_visitors'),
      '#description' => $this->t('Show Total Visitors.'),
    ];

    $form['block']['show_unique_visitor'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Unique Visitors'),
      '#default_value' => $config->get('show_unique_visitor'),
      '#description' => $this->t('Show Unique Visitors based on their IP.'),
    ];

    $form['block']['show_registered_users_count'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Registered Users Count'),
      '#default_value' => $config->get('show_registered_users_count'),
      '#description' => $this->t('Show Registered Users.'),
    ];

    $form['block']['show_last_registered_user'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Last Registered User'),
      '#default_value' => $config->get('show_last_registered_user'),
      '#description' => $this->t('Show Last Registered User.'),
    ];

    $form['block']['show_published_nodes'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Published Nodes'),
      '#default_value' => $config->get('show_published_nodes'),
      '#description' => $this->t('Show Published Nodes.'),
    ];

    $form['block']['show_user_ip'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show User IP'),
      '#default_value' => $config->get('show_user_ip'),
      '#description' => $this->t('Show User IP.'),
    ];

    $form['block']['show_since_date'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Since Date'),
      '#default_value' => $config->get('show_since_date'),
      '#description' => $this->t('Show Since Date.'),
    ];

    $form['block']['start_count_total_visitors'] = [
      '#type' => 'number',
      '#title' => $this->t('Total visitors start count'),
      '#default_value' => $config->get('start_count_total_visitors') ?? 0,
      '#description' => $this->t('Start the count of the total visitors at this number. Useful for including the known number of visitors in the past.'),
    ];

    $form['charts'] = [
      '#type' => 'details',
      '#title' => $this->t('Charts'),
      '#description' => $this->t('Visitors chart settings'),
      '#group' => 'information',
    ];

    $form['charts']['chart_width'] = [
      '#type' => 'number',
      '#title' => $this->t('Width'),
      '#default_value' => $config->get('chart_width') ?? 700,
      '#description' => $this->t('Chart width.'),
    ];

    $form['charts']['chart_height'] = [
      '#type' => 'number',
      '#title' => $this->t('Height'),
      '#default_value' => $config->get('chart_height') ?? 430,
      '#description' => $this->t('Chart height.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable(static::SETTINGS);
    $values = $form_state->getValues();

    $config
      ->set('show_total_visitors', $values['show_total_visitors'])
      ->set('start_count_total_visitors', $values['start_count_total_visitors'])
      ->set('show_unique_visitor', $values['show_unique_visitor'])
      ->set('show_registered_users_count', $values['show_registered_users_count'])
      ->set('show_last_registered_user', $values['show_last_registered_user'])
      ->set('show_published_nodes', $values['show_published_nodes'])
      ->set('show_user_ip', $values['show_user_ip'])
      ->set('show_since_date', $values['show_since_date'])
      ->set('exclude_user1', $values['exclude_user1'])
      ->set('excluded_roles', $values['excluded_roles'])
      ->set('theme', $values['theme'])
      ->set('items_per_page', $values['items_per_page'])
      ->set('flush_log_timer', $values['flush_log_timer'])
      ->set('chart_width', $values['chart_width'])
      ->set('chart_height', $values['chart_height'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
