<?php

namespace Drupal\visitors\Controller\Report;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\Date;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Day of Week Report controller.
 */
class DaysOfWeek extends ControllerBase {
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
   * Constructs a DaysOfWeek object.
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
   * Returns a days of week page page.
   *
   * @return array
   *   A render array representing the days of week page content.
   */
  public function display() {
    $config  = $this->config('visitors.config');
    $form    = $this->formBuilder->getForm('Drupal\visitors\Form\DateFilter');
    $header  = $this->getHeader();
    $results = $this->getData([]);
    $days    = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $x       = [];
    $y       = [];

    foreach ($days as $day) {
      $x[] = '"' . $day . '"';
      $y[$day] = 0;
    }

    foreach ($results as $data) {
      $y[$data[1]->getUntranslatedString()] = $data[2];
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
        'data' => $this->t('#'),
      ],
      'day' => [
        'data' => $this->t('Day'),
      ],
      'count' => [
        'data' => $this->t('Pages'),
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
      visitors_date_format_sql('visitors_date_time', '%a'), 'd'
    );
    $query->addExpression(
      visitors_date_format_sql('MIN(visitors_date_time)', '%w'), 'n'
    );
    visitors_date_filter_sql_condition($query);
    $query->groupBy('d');
    $query->orderBy('n');
    $results = $query->execute();

    $rows = [];
    $tmp_rows = [];

    foreach ($results as $data) {
      $tmp_rows[$data->n] = [
        $data->d,
        $data->count,
        $data->n,
      ];
    }
    $sort_days = $this->getDaysOfWeek();

    foreach ($sort_days as $day => $value) {
      $rows[$value] = [$value, $this->t($day), 0];
    }

    foreach ($tmp_rows as $tmp_item) {
      $day_of_week = Unicode::ucfirst(mb_strtolower($tmp_item[0]));
      $rows[$sort_days[$day_of_week]][2] = $tmp_item[1];
    }

    return $rows;
  }

  /**
   * Create days of week array.
   *
   * Using first_day parameter, using keys as day of week.
   *
   * @return array
   *   An array of sorted days.
   */
  protected function getDaysOfWeek() {
    $days           = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $date_first_day = $this->config('system.date')->get('first_day', 0);
    $sort_days      = [];
    $n              = 1;

    for ($i = $date_first_day; $i < 7; $i++) {
      $sort_days[$days[$i]] = $n++;
    }

    for ($i = 0; $i < $date_first_day; $i++) {
      $sort_days[$days[$i]] = $n++;
    }

    return $sort_days;
  }

}
