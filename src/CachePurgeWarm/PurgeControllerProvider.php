<?php
/**
 * Silex CachePurgeWarm API Controller - Purger API
 *
 * Supplies the routes for the CachePurgeWarm API Purging functionality
 */
/** @namespace CachePurgeWarm */
namespace CachePurgeWarm;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Implementation of ControllerProviderInterface
 */
class PurgeControllerProvider implements ControllerProviderInterface
{
  /**
   * Creates the routes for the API.
   *
   * @param \Silex\Application
   * A Silex Application object
   */
  public function connect(\Silex\Application $app)
  {
    // creates a new controller based on the default route
    $controllers = $app['controllers_factory'];

    $controllers->get('/', function (Application $app) {
      return 'Hello World.';
    });
    $controllers->post('/purge', function (Application $app, Request $request) {
      $cpw_purge = $app['cpw.purge'];
      $purged = $cpw_purge($request);
      return $app->json($purged, 200);
    });

    return $controllers;
  }
}
