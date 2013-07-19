<?php
/**
 * CachePurgeWarm Warmer
 *
 * The CachePurgeWarm Warmer Class conatains the methods to to hit the URLs that
 * need to be warmed.
 *
 * @package CachePurgeWarm
 */

/**
 * Use the CachePurgeWarm namespace
 */
namespace CachePurgeWarm;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This class contains methods to warm urls in a varnish cache
 */
class CPWWarm {
  /**
   * Warms a URL
   *
   * @param \Silex\Application $app
   * A Silex Application
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   * The Symfony Request Object
   *
   */
  public function warm(Application $app, Request $request) {
    $scheme = $request->request->get('scheme');
    $url = $request->request->get('URL');
    $path = $request->request->get('path');
    $request_url = "$scheme://$url$path";
    $guzz_request = $app['guzzle']->createRequest('GET', $request_url);
    try {
      $response = $guzz_request->send();
    } catch (BadResponseException $e) {
      $response = array('status code' => $e->getResponse()->getStatusCode(), 'reason' => $e->getResponse()->getReasonPhrase());
    }
    $return = array(
      'status code' => $response->getStatusCode(),
      'reason' => $response->getReasonPhrase(),
      'url' => $request->request->get('URL'),
      'path' => $request->request->get('path'),
      'warm' => $request->request->get('warm'),
      'scheme' => $request->request->get('scheme')
    );
    return $return;
  }
}
