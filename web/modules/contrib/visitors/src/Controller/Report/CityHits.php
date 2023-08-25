<?php

namespace Drupal\visitors\Controller\Report;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\Date;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * City Hit Report controller.
 */
class CityHits extends ControllerBase {
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
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('form_builder'),
      $container->get('database'),
      $container->get('renderer')
    );
  }

  /**
   * Constructs a CityHits object.
   *
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date service.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database service object.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct(DateFormatterInterface $date_formatter, FormBuilderInterface $form_builder, Connection $database, RendererInterface $renderer) {
    $this->date = $date_formatter;
    $this->formBuilder = $form_builder;
    $this->database = $database;
    $this->renderer = $renderer;
  }

  /**
   * Returns a city hits page.
   *
   * @return array
   *   A render array representing the city hits page content.
   */
  public function display($country, $city) {
    $form = $this->formBuilder->getForm('Drupal\visitors\Form\DateFilter');
    $header = $this->getHeader();

    return [
      '#title' => $this->t('Hits from') . ' ' . $this->t($city) . ', ' . $this->t($country),
      'visitors_date_filter_form' => $form,
      'visitors_table' => [
        '#type'  => 'table',
        '#header' => $header,
        '#rows'   => $this->getData($header, $country, $city),
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
   * @param string $country
   *   Country.
   * @param string $city
   *   City.
   *
   * @return array
   *   Array representing the table content.
   */
  protected function getData(array $header, $country, $city) {
    $items_per_page = $this->config('visitors.config')->get('items_per_page');
    $original_country = ($country == '(none)') ? '' : $country;
    $original_city = ($city == '(none)') ? '' : $city;

    $query = $this->database->select('visitors', 'v')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->extend('Drupal\Core\Database\Query\TableSortExtender');

    $query->leftJoin('users_field_data', 'u', 'u.uid=v.visitors_id');
    $query->fields(
      'v',
      [
        'visitors_id',
        'visitors_uid',
        'visitors_date_time',
        'visitors_title',
        'visitors_path',
        'visitors_url',
      ]
    );
    $query->fields('u', ['name', 'uid']);
    $query->condition('v.visitors_country_name', $original_country);
    $query->condition('v.visitors_city', $original_city);
    visitors_date_filter_sql_condition($query);
    $query->orderByHeader($header);
    $query->limit($items_per_page);

    $count_query = $this->database->select('visitors', 'v');
    $count_query->addExpression('COUNT(*)');
    $count_query->condition('v.visitors_country_name', $original_country);
    $count_query->condition('v.visitors_city', $original_city);
    visitors_date_filter_sql_condition($count_query);
    $query->setCountQuery($count_query);
    $results = $query->execute();

    $rows = [];

    $page = isset($_GET['page']) ? (int) $_GET['page'] : 0;
    $i = 0 + $page * $items_per_page;

    foreach ($results as $data) {
      $user = $this->entityTypeManager()->getStorage('user')->load($data->visitors_uid);
      $username = ['#type' => 'username', '#account' => $user];
      $rows[] = [
        ++$i,
        $data->visitors_id,
        $this->date->format($data->visitors_date_time, 'short'),
        $data->visitors_url,
        $this->renderer->render($username),
        Link::fromTextAndUrl($this->t('details'), Url::fromRoute('visitors.hit_details', ["hit_id" => $data->visitors_id])),
      ];
    }

    return $rows;
  }

}
