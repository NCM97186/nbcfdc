<?php

namespace Drupal\visitors\Controller\Report;

use Drupal\Component\Utility\Html;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\Date;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Hit Detail Report controller.
 */
class HitDetails extends ControllerBase {
  /**
   * The date service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $date;

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
      $container->get('database')
    );
  }

  /**
   * Constructs a HitDetails object.
   *
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database service object.
   */
  public function __construct(DateFormatterInterface $date_formatter, Connection $database) {
    $this->date = $date_formatter;
    $this->database = $database;
  }

  /**
   * Returns a hit details page.
   *
   * @return array
   *   A render array representing the hit details page content.
   */
  public function display($hit_id) {
    return [
      'visitors_table' => [
        '#type' => 'table',
        '#rows'  => $this->getData($hit_id),
      ],
    ];
  }

  /**
   * Returns a table content.
   *
   * @param int $hit_id
   *   Unique id of the visitors log.
   *
   * @return array
   *   Array representing the table content.
   */
  protected function getData($hit_id) {
    $query = $this->database->select('visitors', 'v');
    $query->leftJoin('users_field_data', 'u', 'u.uid=v.visitors_uid');
    $query->fields('v');
    $query->fields('u', ['name', 'uid']);
    $query->condition('v.visitors_id', (int) $hit_id);
    $hit_details = $query->execute()->fetch();

    $rows = [];

    if ($hit_details) {
      $url          = urldecode($hit_details->visitors_url);
      $referer      = $hit_details->visitors_referer;
      $date         = $this->date->format($hit_details->visitors_date_time, 'large');
      $whois_enable = $this->moduleHandler()->moduleExists('whois');

      $attr = [
        'attributes' => [
          'target' => '_blank',
          'title'  => $this->t('Whois lookup'),
        ],
      ];
      $ip   = $hit_details->visitors_ip;
      $user = $this->entityTypeManager()->getStorage('user')->load($hit_details->visitors_uid);
      // @TODO make url, referer and username as link
      $array = [
        'URL'        => $url,
        'Title'      => Html::escape($hit_details->visitors_title),
        'Referer'    => $referer,
        'Date'       => $date,
        'User'       => $user->getAccountName(),
        'IP'         => $whois_enable ? Link::fromTextAndUrl($ip, Url::fromUri('whois/' . $ip, $attr)) : $ip,
        'User Agent' => Html::escape($hit_details->visitors_user_agent),
      ];

      if ($this->moduleHandler()->moduleExists('visitors_geoip')) {
        $geoip_data_array = [
          'Country'        => Html::escape($hit_details->visitors_country_name),
          'Region'         => Html::escape($hit_details->visitors_region),
          'City'           => Html::escape($hit_details->visitors_city),
          'Postal Code'    => Html::escape($hit_details->visitors_postal_code),
          'Latitude'       => Html::escape($hit_details->visitors_latitude),
          'Longitude'      => Html::escape($hit_details->visitors_longitude),
          'DMA Code'       => Html::escape($hit_details->visitors_dma_code),
          'PSTN Area Code' => Html::escape($hit_details->visitors_area_code),
        ];
        $array = array_merge($array, $geoip_data_array);
      }

      foreach ($array as $key => $value) {
        $rows[] = [['data' => $this->t($key), 'header' => TRUE], $value];
      }
    }

    return $rows;
  }

}
