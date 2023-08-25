<?php

namespace Drupal\visitors\Controller\Report;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\Date;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Host Report controller.
 */
class Hosts extends ControllerBase {
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
   * Constructs a Hosts object.
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
   * Returns a hosts page.
   *
   * @return array
   *   A render array representing the hosts page content.
   */
  public function display() {
    $form = $this->formBuilder->getForm('Drupal\visitors\Form\DateFilter');
    $header = $this->getHeader();

    return [
      'visitors_date_filter_form' => $form,
      'visitors_table' => [
        '#type'  => 'table',
        '#header' => $header,
        '#rows'   => $this->getData($header),
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
      'visitors_ip' => [
        'data'      => $this->t('Host'),
        'field'     => 'visitors_ip',
        'specifier' => 'visitors_ip',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
      ],
      'count' => [
        'data'      => $this->t('Count'),
        'field'     => 'count',
        'specifier' => 'count',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
        'sort'      => 'desc',
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
   *
   * @return array
   *   Array representing the table content.
   */
  protected function getData(array $header) {
    $items_per_page = $this->config('visitors.config')->get('items_per_page');

    $query = $this->database->select('visitors', 'v')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->extend('Drupal\Core\Database\Query\TableSortExtender');

    $query->addExpression('COUNT(*)', 'count');
    $query->fields('v', ['visitors_ip']);
    visitors_date_filter_sql_condition($query);
    $query->groupBy('visitors_ip');
    $query->orderByHeader($header);
    $query->limit($items_per_page);

    $count_query = $this->database->select('visitors', 'v');
    $count_query->addExpression('COUNT(DISTINCT visitors_ip)');
    visitors_date_filter_sql_condition($count_query);
    $query->setCountQuery($count_query);
    $results = $query->execute();

    $whois_enable = $this->moduleHandler()->moduleExists('whois');
    $attr = [
      'attributes' => ['target' => '_blank', 'title' => $this->t('Whois lookup')],
    ];

    $rows = [];

    $page = isset($_GET['page']) ? $_GET['page'] : 0;
    $i = 0 + $page * $items_per_page;

    foreach ($results as $data) {
      $ip = $data->visitors_ip;
      $visitors_host_url = Url::fromRoute('visitors.host_hits', ["host" => $ip]);
      $visitors_host_link = Link::fromTextAndUrl($ip, $visitors_host_url);
      $visitors_host_link = $visitors_host_link->toRenderable();
      // @TODO 8.3.X check if whois enable
      $rows[] = [
        ++$i,
        // $whois_enable ? l($ip, 'whois/' . $ip, $attr) : check_plain($ip),
        $ip,
        $data->count,
        \Drupal::service('renderer')->render($visitors_host_link),
      ];
    }

    return $rows;
  }

}
