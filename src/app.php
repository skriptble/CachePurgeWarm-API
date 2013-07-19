<?php
/**
 * Mail Application file for Silex
 */
use CachePurgeWarm\API;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Guzzle\Http\Client;

/** Bootstraping */
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app['guzzle'] = new Client('/');
$app['CachePurgeWarm.Warm'] = new CachePurgeWarm\CPWWarm();
$app['CachePurgeWarm.Purge'] = new CachePurgeWarm\CPWPurge();
$app->register(new CachePurgeWarm\CachePurgeWarmServiceProvider());
$app->before(function (Request $request) {
  if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
    $data = json_decode($request->getContent(), true);
    $request->request->replace(is_array($data) ? $data : array());
  }
});
/** Mount the controllers **/
$app->mount('v1/warm/', new CachePurgeWarm\WarmControllerProvider());
$app->mount('v1/purge/', new CachePurgeWarm\PurgeControllerProvider());

return $app;
