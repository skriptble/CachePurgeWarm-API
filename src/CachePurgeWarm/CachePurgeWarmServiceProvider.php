<?php
/**
 * Silex CachePurgeWarm Provider
 * @package CachePurgeWarm
 */
namespace CachePurgeWarm;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Implementation of Silex ServiceProviderInterface
 */
class CachePurgeWarmServiceProvider implements ServiceProviderInterface
{
  /**
   * Registers the service with the Silex Application
   *
   * @param \Silex\Application $app
   * A Silex Application object
   */
  public function register(Application $app)
  {
    $app['cpw.warm'] = $app->protect(function (Request $request) use ($app) {
      return $app['CachePurgeWarm.Warm']->warm($app, $request);
    });
    $app['cpw.purge'] = $app->protect(function (Request $request) use ($app) {
      return $app['CachePurgeWarm.Purge']->purge($app, $request);
    });
  }
  /**
   * Run when the Silex Application boots
   *
   * @param \Silex\Application $app
   * A Silex Application object
   */
  public function boot(\Silex\Application $app)
  {
  }
}
