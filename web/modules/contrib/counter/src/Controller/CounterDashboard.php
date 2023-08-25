<?php

namespace Drupal\counter\Controller;

use Drupal\counter\CounterUtility;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller class for the dashboard.
 *
 * @package Drupal\counter\Controller
 */
class CounterDashboard extends ControllerBase {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The counter utility variable for accessing helper functions.
   *
   * @var Drupal\counter\CounterUtility
   */
  protected $counterUtility;

  /**
   * Constructor for the counter dashboard controller.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory for the form.
   * @param \Drupal\counter\CounterUtility $counter_utility
   *   The counter utility variable to access helper functions.
   */
  public function __construct(ConfigFactoryInterface $config_factory, CounterUtility $counter_utility) {
    $this->configFactory = $config_factory;
    $this->counterUtility = $counter_utility;
  }

  /**
   * Create function to assemble the counter dashboard.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container interface.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('counter.counter_utility')
    );
  }

  /**
   * The Dashboard page where counter statistics are displayed to the user.
   */
  public function page() {
    $config = $this->configFactory->get('counter.settings');
    $counter_initial_counter = $config->get('counter_initial_counter');
    $counter_initial_unique_visitor = $config->get('counter_initial_unique_visitor');
    $counter_initial_since = $config->get('counter_initial_since');

    $site_counter = number_format($counter_initial_counter + $this->counterUtility->getVisitorData());
    $unique_visitor = number_format($counter_initial_unique_visitor + $this->counterUtility->getUniqueVisitorData());
    $registered_user = number_format($this->counterUtility->getTotalUsers());
    $unregistered_user = number_format($this->counterUtility->getTotalUsers('='));
    $blocked_user = number_format($this->counterUtility->getTotalUsers('=', 0));
    $published_nodes = number_format($this->counterUtility->getTotalNodes());
    $unpublished_nodes = number_format($this->counterUtility->getTotalNodes(0));

    $counter_since = $this->counterUtility->getCounterLastDate('>', 'ASC');
    $statistic_today = number_format($this->counterUtility->getTimeRangeData(strtotime(date('Y-m-d'))));
    $statistic_week = number_format($this->counterUtility->getTimeRangeData(strtotime(date('Y-m-d')) - 7 * 24 * 60 * 60, time()));
    $statistic_month = number_format($this->counterUtility->getTimeRangeData(strtotime(date('Y-m-d')) - 30 * 24 * 60 * 60, time()));
    $statistic_year = number_format($this->counterUtility->getTimeRangeData(strtotime(date('Y-m-d')) - 365 * 24 * 60 * 60, time()));

    if ($counter_initial_since != 0) {
      $counter_since = $counter_initial_since;
    }
    $counter_since = date('d M Y', $counter_since);

    return [
      '#theme' => 'counter_dashboard',
      '#attached' => [
        'library' => ['counter/counter.dashboard'],
      ],
      '#site_counter' => $site_counter,
      '#unique_visitor' => $unique_visitor,
      '#registered_user' => $registered_user,
      '#unregistered_user' => $unregistered_user,
      '#blocked_user' => $blocked_user,
      '#published_nodes' => $published_nodes,
      '#unpublished_nodes' => $unpublished_nodes,
      '#counter_since' => $counter_since,
      '#statistic_today' => $statistic_today,
      '#statistic_week' => $statistic_week,
      '#statistic_month' => $statistic_month,
      '#statistic_year' => $statistic_year,
    ];
  }

}
