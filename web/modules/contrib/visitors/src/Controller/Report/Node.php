<?php

namespace Drupal\visitors\Controller\Report;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Query\Condition;
use Drupal\Core\Datetime\Date;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Node Report controller.
 */
class Node extends ControllerBase {
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
   * Constructs a Node object.
   *
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date service.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database service object.
   */
  public function __construct(DateFormatterInterface $date_formatter, FormBuilderInterface $form_builder, Connection $database) {
    $this->date = $date_formatter;
    $this->formBuilder = $form_builder;
    $this->database = $database;
  }

  /**
   * Returns a recent hits page.
   *
   * @return array
   *   A render array representing the recent hits page content.
   */
  public function display(NodeInterface $node) {
    $form = $this->formBuilder->getForm('Drupal\visitors\Form\DateFilter');
    $header = $this->getHeader();

    return [
      'visitors_date_filter_form' => $form,
      'visitors_table' => [
        '#type'  => 'table',
        '#header' => $header,
        '#rows'   => $this->getData($header, $node),
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
      'visitors_referer' => [
        'data'      => $this->t('Referer'),
        'field'     => 'visitors_referer',
        'specifier' => 'visitors_referer',
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
   * @param Drupal\node\NodeInterface $node
   *   Table header configuration.
   *
   * @return array
   *   Array representing the table content.
   */
  protected function getData(array $header, NodeInterface $node) {
    if ($node) {
      $items_per_page = $this->config('visitors.config')->get('items_per_page');
      $query = $this->database->select('visitors', 'v')
        ->extend('Drupal\Core\Database\Query\PagerSelectExtender')
        ->extend('Drupal\Core\Database\Query\TableSortExtender');
      $query->leftJoin('users_field_data', 'u', 'u.uid=v.visitors_id');
      $query->fields(
      'v',
      [
        'visitors_uid',
        'visitors_id',
        'visitors_date_time',
        'visitors_referer',
      ]
        );

      $nid = (int) $node->id();
      $query->fields('u', ['name', 'uid']);
      $db_or = new Condition('or');
      $db_or->condition('v.visitors_path', '/node/' . $nid, '=');
      // @todo removed placeholder is this right?
      $db_or->condition(
        'v.visitors_path', '%/node/' . $nid . "%", 'LIKE'
        );
      $query->condition($db_or);

      visitors_date_filter_sql_condition($query);
      $query->orderByHeader($header);
      $query->limit($items_per_page);

      $count_query = $this->database->select('visitors', 'v');
      $count_query->addExpression('COUNT(*)');
      $count_query->condition($db_or);
      visitors_date_filter_sql_condition($count_query);
      $query->setCountQuery($count_query);
      $results = $query->execute();
      $rows = [];

      $page = isset($_GET['page']) ? (int) $_GET['page'] : 0;
      $i = 0 + $page * $items_per_page;

      foreach ($results as $data) {
        $user = $this->entityTypeManager()->getStorage('user')->load($data->visitors_uid);
        $username = [
          '#type' => 'username',
          '#account' => $user,
        ];
        $rows[] = [
          ++$i,
          $data->visitors_id,
          $this->date->format($data->visitors_date_time, 'short'),
          !empty($data->visitors_referer) ? $data->visitors_referer : 'none',
          $user->getAccountName(),
          Link::fromTextAndUrl($this->t('details'), Url::fromRoute('visitors.hit_details', ["hit_id" => $data->visitors_id])),
        ];
      }

      return $rows;
    }
  }

}
