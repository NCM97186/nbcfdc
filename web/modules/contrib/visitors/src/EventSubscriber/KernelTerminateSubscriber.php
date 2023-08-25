<?php

namespace Drupal\visitors\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\TitleResolverInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Routing\AdminContext;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\State\StateInterface;

/**
 * Store visitors data when a request terminates.
 */
class KernelTerminateSubscriber implements EventSubscriberInterface {
  /**
   * The currently active request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Database Service Object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The title resolver.
   *
   * @var \Drupal\Core\Controller\TitleResolverInterface
   */
  protected $titleResolver;

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The admin context service.
   *
   * @var \Drupal\Core\Routing\AdminContext
   */
  protected $adminContext;

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Database\Connection $database
   *   The database service object.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match object.
   * @param \Drupal\Core\Controller\TitleResolverInterface $title_resolver
   *   The title_resolver service object.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config_factory service object.
   * @param \Drupal\Core\Routing\AdminContext $admin_context
   *   The admin context object.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler object.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(AccountInterface $current_user, Connection $database, RouteMatchInterface $route_match, TitleResolverInterface $title_resolver, ConfigFactoryInterface $config_factory, AdminContext $admin_context, ModuleHandlerInterface $module_handler, StateInterface $state) {
    $this->currentUser = $current_user;
    $this->database = $database;
    $this->routeMatch = $route_match;
    $this->titleResolver = $title_resolver;
    $this->configFactory = $config_factory;
    $this->adminContext = $admin_context;
    $this->moduleHandler = $module_handler;
    $this->state = $state;
  }

  /**
   * Store visitors data when a request terminates.
   *
   * @param \Symfony\Component\HttpKernel\Event\TerminateEvent $event
   *   The Event to process.
   */
  public function onTerminate(TerminateEvent $event) {
    if (1 == $this->state->get('system.maintenance_mode')) {
      return NULL;
    }
    $this->request = $event->getRequest();
    $config = $this->configFactory->get('visitors.config');
    $exclude_user1 = $config->get('exclude_user1');
    $excluded_roles = array_values($config->get('excluded_roles') ?? []);

    $skip_because_user1 = $this->currentUser->id() == 1 && $exclude_user1;
    if ($skip_because_user1) {
      return NULL;
    }

    $user_roles = $this->currentUser->getRoles();
    foreach ($excluded_roles as $role) {
      $skip_because_role = in_array($role, $user_roles, TRUE);
      if ($skip_because_role) {
        return NULL;
      }
    }

    $ip_str = $this->getIpStr();
    $fields = [
      'visitors_uid'        => $this->currentUser->id(),
      'visitors_ip'         => $ip_str,
      'visitors_date_time'  => time(),
      'visitors_url'        => $this->getUrl(),
      'visitors_referer'    => $this->getReferer(),
      'visitors_path'       => Url::fromRoute('<current>')->toString(),
      'visitors_title'      => $this->getTitle(),
      'visitors_user_agent' => $this->getUserAgent(),
    ];

    if ($this->moduleHandler->moduleExists('visitors_geoip')) {
      $geoip_data = $this->getGeoipData($ip_str);

      $fields['visitors_continent_code'] = $geoip_data['continent_code'];
      $fields['visitors_country_code']   = $geoip_data['country_code'];
      $fields['visitors_country_code3']  = $geoip_data['country_code3'];
      $fields['visitors_country_name']   = $geoip_data['country_name'];
      $fields['visitors_region']         = $geoip_data['region'];
      $fields['visitors_city']           = $geoip_data['city'];
      $fields['visitors_postal_code']    = $geoip_data['postal_code'];
      $fields['visitors_latitude']       = $geoip_data['latitude'];
      $fields['visitors_longitude']      = $geoip_data['longitude'];
      $fields['visitors_dma_code']       = $geoip_data['dma_code'];
      $fields['visitors_area_code']      = $geoip_data['area_code'];
    }

    try {
      $this->database->insert('visitors')
        ->fields($fields)
        ->execute();
    }
    catch (\Exception $e) {
      watchdog_exception('visitors', $e);
    }
  }

  /**
   * Registers the methods in this class that should be listeners.
   *
   * @return array
   *   An array of event listener definitions.
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::TERMINATE][] = ['onTerminate'];
    return $events;
  }

  /**
   * Get the title of the current page.
   *
   * @return string
   *   Title of the current page.
   */
  protected function getTitle() {
    $title = '';
    $routeObject = $this->routeMatch->getRouteObject();
    if (!is_null($routeObject)) {
      $title = $this->titleResolver->getTitle($this->request, $routeObject);
    }

    if (is_array($title)) {
      return htmlspecialchars_decode($title['#markup'] ?? '', ENT_QUOTES);
    }

    return htmlspecialchars_decode($title ?? '', ENT_QUOTES);
  }

  /**
   * Get full path request uri.
   *
   * @return string
   *   Full path.
   */
  protected function getUrl() {
    $host = array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : '';
    $uri = $this->request->getRequestUri();

    return urldecode(sprintf('http://%s%s', $host, $uri));
  }

  /**
   * Get the address of the page.
   *
   * If any, which referred the user agent to the current page.
   *
   * @return string
   *   Referer, or empty string if referer does not exist.
   */
  protected function getReferer() {
    return isset($_SERVER['HTTP_REFERER']) ? urldecode($_SERVER['HTTP_REFERER']) : '';
  }

  /**
   * Converts a string.
   *
   * Containing a visitors (IPv4) Internet Protocol dotted
   * address into a proper address.
   *
   * @return string
   *   IPv4) Internet Protocol dotted address.
   */
  protected function getIpStr() {
    return $this->request->getClientIp();
  }

  /**
   * Get visitor user agent.
   *
   * @return string
   *   string user agent, or empty string if user agent does not exist
   */
  protected function getUserAgent() {
    return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
  }

  /**
   * Retrieve geoip data for ip.
   *
   * @param string $ip
   *   A string containing an ip address.
   *
   * @return array
   *   Geoip data array.
   */
  protected function getGeoipData($ip) {
    $result = [
      'continent_code' => '',
      'country_code'   => '',
      'country_code3'  => '',
      'country_name'   => '',
      'region'         => '',
      'city'           => '',
      'postal_code'    => '',
      'latitude'       => '0',
      'longitude'      => '0',
      'dma_code'       => '0',
      'area_code'      => '0',
    ];

    if (function_exists('geoip_record_by_name')) {
      $data = @geoip_record_by_name($ip);
      if ((!is_null($data)) && ($data !== FALSE)) {
        /* Transform city value from iso-8859-1 into the utf8. */
        $data['city'] = utf8_encode($data['city']);

        $result = $data;
      }
    }

    return $result;
  }

}
