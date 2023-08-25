<?php

namespace Drupal\counter\EventSubscriber;

use Drupal\counter\CounterUtility;
use Drupal\node\NodeInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class CounterEventSubscriber.
 *
 * @package Drupal\counter\EventSubscriber
 */
class CounterEventsSubscriber implements EventSubscriberInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The account interface for accessing current user.
   *
   * @var Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * The counter utility variable for accessing helper functions.
   *
   * @var Drupal\counter\CounterUtility
   */
  protected $counterUtility;

  /**
   * Constructs an event subscriber.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory for the form.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account interface for accessing current user.
   * @param \Drupal\counter\CounterUtility $counter_utility
   *   The counter utility variable to access helper functions.
   */
  public function __construct(ConfigFactoryInterface $config_factory, AccountInterface $account, CounterUtility $counter_utility) {
    $this->configFactory = $config_factory;
    $this->account = $account;
    $this->counterUtility = $counter_utility;
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => 'counterInsert',
    ];
  }

  /**
   * React to a HTTP event.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   Request event.
   */
  public function counterInsert(RequestEvent $event) {
    // Get configuration related to skipping admin counts.
    $config = $this->configFactory->get('counter.settings');
    $counter_skip_admin = $config->get('counter_skip_admin');

    // Return in case counter has to be skipped for administrators.
    if (in_array('administrator', $this->account->getRoles()) && $counter_skip_admin) {
      return;
    }

    // Fetch basic info from request.
    $request = $event->getRequest();
    $data['ip'] = $request->getClientIp();
    $data['url'] = Html::escape($request->getRequestUri());
    $data['uid'] = $this->account->id();

    // Get Node ID, content type, browser name, browser version and platform.
    $node = $request->attributes->get('node');
    $data['nid'] = 0;
    $data['type'] = '';
    if ($node instanceof NodeInterface) {
      $data['nid'] = $node->id();
      $data['type'] = $node->bundle();
    }
    $browser = $this->counterUtility->getBrowserInformation();
    $data['browser_name'] = $browser['browser_name'];
    $data['browser_version'] = $browser['browser_version'];
    $data['platform'] = $browser['platform'];

    $this->counterUtility->insertCounterData($data);
  }

}
