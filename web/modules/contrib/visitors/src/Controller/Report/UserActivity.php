<?php

namespace Drupal\visitors\Controller\Report;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\Date;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * User Activity Report controller.
 */
class UserActivity extends ControllerBase {
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
   * Constructs a UserActivity object.
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
   * Returns a user activity page.
   *
   * @return array
   *   A render array representing the user activity page content.
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
    $headers = [
      '#' => [
        'data'      => $this->t('#'),
      ],
      'u.name' => [
        'data'      => $this->t('User'),
        'field'     => 'u.name',
        'specifier' => 'u.name',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
      ],
      'hits' => [
        'data'      => $this->t('Hits'),
        'field'     => 'hits',
        'specifier' => 'hits',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
        'sort'      => 'desc',
      ],
      'nodes' => [
        'data'      => $this->t('Nodes'),
        'field'     => 'nodes',
        'specifier' => 'nodes',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
      ],
    ];

    if ($this->moduleHandler()->moduleExists('comment')) {
      $headers['comments'] = [
        'data'      => $this->t('Comments'),
        'field'     => 'comments',
        'specifier' => 'comments',
        'class'     => [RESPONSIVE_PRIORITY_LOW],
      ];
    }

    return $headers;
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
    $is_comment_module_exist = $this->moduleHandler()->moduleExists('comment');
    $items_per_page = $this->config('visitors.config')->get('items_per_page');

    $query = $this->database->select('users_field_data', 'u')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->extend('Drupal\Core\Database\Query\TableSortExtender');

    $query->leftJoin('visitors', 'v', 'u.uid=v.visitors_uid');
    $query->leftJoin('node_field_data', 'nfd', 'nfd.uid=v.visitors_uid');
    $query->leftJoin('node', 'n', 'nfd.nid=n.nid');
    if ($is_comment_module_exist) {
      $query->leftJoin('comment_field_data', 'c', 'u.uid=c.uid');
    }
    $query->fields('u', ['name', 'uid']);
    $query->addExpression('COUNT(DISTINCT v.visitors_id)', 'hits');
    $query->addExpression('COUNT(DISTINCT n.nid)', 'nodes');
    if ($is_comment_module_exist) {
      $query->addExpression('COUNT(DISTINCT c.cid)', 'comments');
    }
    visitors_date_filter_sql_condition($query);
    $query->groupBy('u.name');
    $query->groupBy('u.uid');
    $query->groupBy('v.visitors_uid');
    $query->groupBy('nfd.uid');
    if ($is_comment_module_exist) {
      $query->groupBy('c.uid');
    }
    $query->orderByHeader($header);
    $query->limit($items_per_page);

    $count_query = $this->database->select('users_field_data', 'u');
    $count_query->leftJoin('visitors', 'v', 'u.uid=v.visitors_uid');
    $count_query->addExpression('COUNT(DISTINCT u.uid)');
    visitors_date_filter_sql_condition($count_query);
    $query->setCountQuery($count_query);
    $results = $query->execute();

    $rows = [];

    $page = isset($_GET['page']) ? $_GET['page'] : 0;
    $i = 0 + $page * $items_per_page;

    foreach ($results as $data) {
      $user = $this->entityTypeManager()->getStorage('user')->load($data->uid);
      if ($is_comment_module_exist) {
        $rows[] = [
          ++$i,
          ($user->id() == 0) ? 'Anonymous User' : $user->getAccountName(),
          $data->hits,
          $data->nodes,
          $data->comments,
        ];
      }
      else {
        $rows[] = [
          ++$i,
          ($user->id() == 0) ? 'Anonymous User' : $user->getAccountName(),
          $data->hits,
          $data->nodes,
        ];
      }
    }

    return $rows;
  }

}
