<?php

namespace Drupal\visitors\Controller\Report;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\Date;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Day of Month Report controller.
 */
class DaysOfMonth extends ControllerBase {
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
   * Constructs a DaysOfMonth object.
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
   * Returns a days of month page.
   *
   * @return array
   *   A render array representing the days of month page content.
   */
  public function display() {
    $config  = $this->config('visitors.config');
    $form    = $this->formBuilder->getForm('Drupal\visitors\Form\DateFilter');
    $header  = $this->getHeader();
    $results = $this->getData([]);

    $y = [];
    for ($i = 1; $i <= 31; $i++) {
      $y[$i] = 0;
    }

    foreach ($results as $data) {
      $y[$data[1]] = $data[2];
    }

    return [
      'visitors_date_filter_form' => $form,
      'visitors_jqplot' => [
        '#theme'  => 'visitors_jqplot',
        '#path'   => \Drupal::service('extension.list.module')->getPath('visitors'),
        '#x'      => implode(', ', range(1, 31)),
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
      'day' => [
        'data'      => $this->t('Day'),
        'field'     => 'day',
        'specifier' => 'day',
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
    $query = $this->database->select('visitors', 'v');
    $query->addExpression('COUNT(*)', 'count');
    $query->addExpression(
      visitors_date_format_sql('visitors_date_time', '%d'), 'day'
    );
    $query->groupBy('day');
    visitors_date_filter_sql_condition($query);

    if (!is_null($header)) {
      $query
        ->extend('Drupal\Core\Database\Query\TableSortExtender')
        ->orderByHeader($header);
    }

    $results = $query->execute();
    $rows = [];
    $i = 0;

    foreach ($results as $data) {
      $rows[] = [
        ++$i,
        (int) $data->day,
        $data->count,
      ];
    }

    return $rows;
  }

}
