<?php

namespace Drupal\cmf_google_search\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a controller for displaying search results from Google CSE.
 */
class SearchPage extends ControllerBase {

  /**
   * Configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $searchConfig;

  /**
   * Constructs a new SearchPage object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config) {
    $this->searchConfig = $config->get('cmf_google_search.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $containerInterface) {
    return new static(
      $containerInterface->get('config.factory')
    );
  }

  /**
   * Function responsible for returning the search results page.
   */
  public function displaySearchResults() {
    // Display the results returned by Google.
    return [
      '#type' => 'html_tag',
      '#tag' => 'gcse:searchresults-only',
      '#attributes' => [
        'queryParameterName' => "s",
        'linktarget' => '_parent'
      ],
      '#value' => 'Please make sure javascript is enabled to see the search results.',
      '#attached' => [
        'library' => ['cmf_google_search/search'],
        'drupalSettings' => [
          'cmf_google_search' => [
            'cx' => $this->searchConfig->get('cx'),
          ]
        ]
      ],
    ];
  }
}