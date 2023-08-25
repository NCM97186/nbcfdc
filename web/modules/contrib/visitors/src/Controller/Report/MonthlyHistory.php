<?php

namespace Drupal\visitors\Controller\Report;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\Date;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Monthly History Report controller.
 */
class MonthlyHistory extends ControllerBase {
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
   * Constructs a MonthlyHistory object.
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
   * Returns a monthly history page.
   *
   * @return array
   *   A render array representing the monthly history page content.
   */
  public function display() {
    $config  = $this->config('visitors.config');
    $form    = $this->formBuilder->getForm('Drupal\visitors\Form\DateFilter');
    $header  = $this->getHeader();
    $results = $this->getData([]);
    $x       = [];
    $y       = [];

    foreach ($results as $data) {
      $x[] = "\"$data[1]\"";
      $y[$data[1]] = $data[2];
    }

    return [
      'visitors_date_filter_form' => $form,
      'visitors_jqplot' => [
        '#theme'  => 'visitors_jqplot',
        '#path'   => \Drupal::service('extension.list.module')->getPath('visitors'),
        '#x'      => implode(', ', $x),
        '#y'      => implode(', ', $y),
        '#width'  => $config->get('chart_width'),
        '#height' => $config->get('chart_height'),
      ],
      'visitors_table' => [
        '#type'  => 'table',
        '#header' => $header,
        '#rows'   => $this->getData($header),
      ],
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
      'm' => [
        'data'      => $this->t('Month'),
        'field'     => 'm',
        'specifier' => 'm',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
        'sort'      => 'asc',
      ],
      'count' => [
        'data'      => $this->t('Pages'),
        'field'     => 'count',
        'specifier' => 'count',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
      ],
    ];
  }

  /**
   * Returns a table content.
   *
   * @param array $header
   *   Table header configuration.
   *
   * @return array
   *   Array representing the table content.
   */
  protected function getData(array $header) {
    $items_per_page = $this->config('visitors.config')->get('items_per_page');

    $query = $this->database->select('visitors', 'v')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender');

    $query->addExpression('COUNT(*)', 'count');
    $query->addExpression(
      visitors_date_format_sql('visitors_date_time', '%Y%m'), 'm'
    );
    $query->addExpression(
      visitors_date_format_sql('MIN(visitors_date_time)', '%Y %M'), 's'
    );
    visitors_date_filter_sql_condition($query);
    $query->groupBy('m');
    if (!is_null($header)) {
      $query
        ->extend('Drupal\Core\Database\Query\TableSortExtender')
        ->orderByHeader($header);
    }
    $query->limit($items_per_page);

    $count_query = $this->database->select('visitors', 'v');
    $count_query->addExpression(
      sprintf('COUNT(DISTINCT %s)',
      visitors_date_format_sql('visitors_date_time', '%Y %M'))
    );

    visitors_date_filter_sql_condition($count_query);
    $query->setCountQuery($count_query);
    $results = $query->execute();

    $rows = [];

    $page = isset($_GET['page']) ? (int) $_GET['page'] : 0;
    $i = 0 + $page * $items_per_page;

    foreach ($results as $data) {
      $rows[] = [
        ++$i,
        $data->s,
        $data->count,
      ];
    }

    return $rows;
  }

}
