<?php

namespace Drupal\ip;

use Drupal\Core\Database\Connection;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Session\AccountInterface;

/*
 * @file
 * IpTracker Manager.
 */

class IpTracker {

  private $connection;

  private $account;

  private $request;

  function __construct(Connection $connection, Request $request, AccountInterface $account) {
    $this->connection = $connection;
    $this->request = $request;
    $this->account = $account;
  }

  /**
   * Save the IpTrack
   */
  function save() {

    $ip = $this->request->getClientIp();
    $uid = $this->account->id();

    $iplong = ip2long($ip);

    if (!empty($iplong)) {

      // Check to see if a row exists for this uid/ip combination.
      $sql = "SELECT visits FROM {ip_tracker} WHERE uid = :uid AND ip = :ip";
      $args = [':uid' => $uid, ':ip' => $iplong];
      $visits = $this->connection->query($sql, $args)->fetchField();

      if ($visits) {
        // Update.
        $this->connection->update('ip_tracker')
          ->fields(
            [
              'visits' => $visits + 1,
              'last_visit' => \Drupal::time()->getRequestTime(),
            ]
          )
          ->condition('uid', $uid)
          ->condition('ip', $iplong)
          ->execute();
      }
      else {
        // Insert.
        $this->connection->insert('ip_tracker')
          ->fields(
            [
              'uid' => $uid,
              'ip' => $iplong,
              'visits' => 1,
              'first_visit' => \Drupal::time()->getRequestTime(),
              'last_visit' => \Drupal::time()->getRequestTime(),
            ]
          )
          ->execute();
      }

    }

  }

  /**
   * Remove records in the ip_tracker table for a certain user.
   */
  function remove() {
    return $this->connection->delete('ip_tracker')
      ->condition('uid', $this->account->id())
      ->execute();
  }

}
