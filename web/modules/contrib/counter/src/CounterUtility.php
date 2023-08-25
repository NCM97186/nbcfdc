<?php

namespace Drupal\counter;

use DeviceDetector\DeviceDetector;
use Drupal\Core\Database\Connection;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class that provides utility functions for the counter module.
 *
 * @package Drupal\counter
 */
class CounterUtility {
  /**
   * The database connection.
   *
   * @var Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The request stack.
   *
   * @var Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The device detector.
   *
   * @var \DeviceDetector\DeviceDetector\DeviceDetector
   */
  protected $deviceDetector;

  /**
   * Constructs an event subscriber.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection variable for executing queries.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack for accessing request information.
   * @param \DeviceDetector\DeviceDetector $device_detector
   *   The device detector for getting accessing device details.
   */
  public function __construct(Connection $database, RequestStack $request_stack, DeviceDetector $device_detector) {
    $this->database = $database;
    $this->requestStack = $request_stack;
    $this->deviceDetector = $device_detector;

    // Setting up user agent based on current request.
    $this->deviceDetector->setUserAgent($this->requestStack->getCurrentRequest()->server->get('HTTP_USER_AGENT'));
    $this->deviceDetector->parse();
  }

  /**
   * Retrieves counter last date from database.
   */
  public function getCounterLastDate($operator = '<>', $order = 'DESC') {
    $query = $this->database->select('counter', 'c');
    $query->condition('c.created', 0, $operator);
    $query->fields('c', ['created']);
    $query->orderBy('created', $order);
    $query->range(0, 1);
    $result = $query->execute();

    return $result->fetchField();
  }

  /**
   * Fetches browser and platform information.
   */
  public function getBrowserInformation() {
    $browser_name = 'Unknown';
    $browser_version = '';
    $platform = 'Unknown';

    // Return unknown in case user is a bot.
    if ($this->deviceDetector->isBot()) {
      return [
        'browser_name' => $browser_name,
        'browser_version' => $browser_version,
        'platform' => $platform,
      ];
    }

    // Fetching required information using helper functions.
    $browser_name = $this->deviceDetector->getClient('name');
    $browser_version = $this->deviceDetector->getClient('version');
    $operating_system = $this->deviceDetector->getOs();
    if (array_key_exists('name', $operating_system)) {
      $platform = $this->deviceDetector->getOs()['name'];
    }

    return [
      'browser_name' => $browser_name,
      'browser_version' => $browser_version,
      'platform' => $platform,
    ];
  }

  /**
   * Inserts counter data to database.
   */
  public function insertCounterData(array $data) {
    $this->database->insert('counter')
      ->fields([
        'ip' => $data['ip'],
        'created' => time(),
        'url' => $data['url'],
        'uid' => $data['uid'],
        'nid' => $data['nid'],
        'type' => $data['type'],
        'browser_name' => $data['browser_name'],
        'browser_version' => $data['browser_version'],
        'platform' => $data['platform'],
      ])
      ->execute();
  }

  /**
   * Retrieves visitors from the counter table.
   */
  public function getVisitorData() {
    $query = $this->database->select('counter', 'c');
    $query->fields('c');
    $result = $query->countQuery()->execute()->fetchField();

    return $result;
  }

  /**
   * Retrieves unique visitors from the counter table.
   */
  public function getUniqueVisitorData() {
    $query = $this->database->select('counter', 'c');
    $query->fields('c', ['ip']);
    $query->groupBy('c.ip');
    $result = $query->countQuery()->execute()->fetchField();

    return $result;
  }

  /**
   * Retrieves data related to users in the website.
   */
  public function getTotalUsers($operator = '<>', $status = 1) {
    $query = $this->database->select('users_field_data', 'u');
    $query->fields('u');
    $query->condition('u.access', 0, $operator);
    $query->condition('u.uid', 0, '<>');
    $query->condition('u.status', $status);
    $result = $query->countQuery()->execute()->fetchField();

    return $result;
  }

  /**
   * Retrieves data related to nodes in the website.
   */
  public function getTotalNodes($status = 1) {
    $query = $this->database->select('node_field_data', 'n');
    $query->fields('n');
    $query->condition('n.status', $status);
    $result = $query->countQuery()->execute()->fetchField();

    return $result;
  }

  /**
   * Retrieves data based on time span.
   */
  public function getTimeRangeData($date1, $date2 = 0) {
    $query = $this->database->select('counter', 'c');
    $query->fields('c');
    $query->condition('c.created', $date1, '>');
    if ($date2 != 0) {
      $query->condition('c.created', $date2, '<');
    }
    $result = $query->countQuery()->execute()->fetchField();

    return $result;
  }

}
