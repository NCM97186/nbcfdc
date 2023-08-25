<?php

namespace Drupal\visitors\Controller\Report;

use Drupal\Component\Utility\Html;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\Date;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Hit Report controller.
 */
class Hits extends ControllerBase {
  /**
   * The date service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $date;

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Database Service Object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('form_builder'),
      $container->get('database')
    );
  }

  /**
   * Constructs a Hits object.
   *
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date service.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database service object.
   */
  public function __construct(DateFormatterInterface $date_formatter, FormBuilderInterface $form_builder, Connection $database) {
    $this->date        = $date_formatter;
    $this->formBuilder = $form_builder;
    $this->database = $database;
  }

  /**
   * Returns a hits page.
   *
   * @param string $host
   *   The hostname.
   *
   * @return array
   *   A render array representing the hits page content.
   */
  public function display($host) {
    $form = $this->formBuilder->getForm('Drupal\visitors\Form\DateFilter');
    $header = $this->getHeader();

    return [
      '#title' => Html::escape($this->t('Hits from') . ' ' . $host),
      'visitors_date_filter_form' => $form,
      'visitors_table' => [
        '#type'  => 'table',
        '#header' => $header,
        '#rows'   => $this->getData($header, $host),
      ],
      'visitors_pager' => ['#type' => 'pager'],
    ];
  }

  /**
   * Returns a table header configuration.
   *
   * @return array
   *   A render array representing the table header info.
   */
  protected function getHeader() {
    return [
      '#' => [
        'data'      => $this->t('#'),
      ],
      'visitors_id' => [
        'data'      => $this->t('ID'),
        'field'     => 'visitors_id',
        'specifier' => 'visitors_id',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
        'sort'      => 'desc',
      ],
      'visitors_date_time' => [
        'data'      => $this->t('Date'),
        'field'     => 'visitors_date_time',
        'specifier' => 'visitors_date_time',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
      ],
      'visitors_url' => [
        'data'      => $this->t('URL'),
        'field'     => 'visitors_url',
        'specifier' => 'visitors_url',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
      ],
      'u.name' => [
        'data'      => $this->t('User'),
        'field'     => 'u.name',
        'specifier' => 'u.name',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
      ],
      '' => [
        'data'      => $this->t('Operations'),
      ],
    ];
  }

  /**
   * Returns a table content.
   *
   * @param array $header
   *   Table header configuration.
   * @param string $host
   *   Ip address of the host.
   *
   * @return array
   *   Array representing the table content.
   */
  protected function getData(array $header, $host) {
    if (inet_pton($host) === FALSE) {
      return NULL;
    }

    $items_per_page = $this->config('visitors.config')->get('items_per_page');

    $query = $this->database->select('visitors', 'v')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->extend('Drupal\Core\Database\Query\TableSortExtender');

    $query->leftJoin('users_field_data', 'u', 'u.uid=v.visitors_uid');
    $query->fields(
      'v',
      [
        'visitors_id',
        'visitors_ip',
        'visitors_uid',
        'visitors_date_time',
        'visitors_title',
        'visitors_path',
        'visitors_url',
      ]
    );
    $query->fields('u', ['name', 'uid']);
    $query->condition('v.visitors_ip', $host, '=');
    visitors_date_filter_sql_condition($query);
    $query->orderByHeader($header);
    $query->limit($items_per_page);

    $count_query = $this->database->select('visitors', 'v');
    $count_query->addExpression('COUNT(*)');
    $count_query->condition('visitors_ip', $host);
    visitors_date_filter_sql_condition($count_query);
    $query->setCountQuery($count_query);
    $results = $query->execute();

    $count = $count_query->execute()->fetchField();
    if ($count == 0) {
      return NULL;
    }
    $rows = [];

    $page = isset($_GET['page']) ? $_GET['page'] : 0;
    $i = 0 + $page * $items_per_page;

    foreach ($results as $data) {
      $user = $this->entityTypeManager()->getStorage('user')->load($data->visitors_uid);
      $username = ['#type' => 'username', '#account' => $user];

      $visitors_host_url = Url::fromRoute('visitors.hit_details', ["hit_id" => $data->visitors_id]);
      $visitors_host_link = Link::fromTextAndUrl($this->t('Details'), $visitors_host_url);
      $visitors_host_link = $visitors_host_link->toRenderable();

      $user_profile_url = Url::fromRoute('entity.user.canonical', ["user" => $user->id()]);
      $user_profile_link = Link::fromTextAndUrl($user->getAccountName(), $user_profile_url);
      $user_profile_link = $user_profile_link->toRenderable();

      $rows[] = [
        ++$i,
        $data->visitors_id,
        $this->date->format($data->visitors_date_time, 'short'),
        Html::escape($data->visitors_title) . " - " . $data->visitors_url,
        \Drupal::service('renderer')->render($user_profile_link),
        \Drupal::service('renderer')->render($visitors_host_link),
      ];
    }

    return $rows;
  }

}
