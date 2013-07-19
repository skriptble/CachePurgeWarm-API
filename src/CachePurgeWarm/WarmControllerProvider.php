<?php
/**
 * Silex CachePurgeWarm API Controller - Warmer API
 *
 * Supplies the routes for the CachePurgeWarm API Warming functionality
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
class WarmControllerProvider implements ControllerProviderInterface
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
      $return = array(
        'status' => 'ready',
        'awesome' => 'true',
        'type' => 'warm'
      );

      return $app->json($return, 200);
    });
    $controllers->post('/warm', function (Application $app, Request $request) {
      $cpw_warm = $app['cpw.warm'];
      $warmed = $cpw_warm($request);
      return $app->json($warmed, 200);
    });

    return $controllers;
  }
}
