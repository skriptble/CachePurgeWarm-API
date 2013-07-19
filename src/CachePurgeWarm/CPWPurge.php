<?php
/**
 * CachePurgeWarm Purger
 *
 * The CachePurgeWarm Purger Class conatains the methods to to hit the URLs that
 * need to be purged.
 *
 * @package CachePurgeWarm
 */

/**
 * Use the CachePurgeWarm namespace
 */
namespace CachePurgeWarm;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Guzzle\Http\Exception\BadResponseException;

/**
 * This class contains methods to warm urls in a varnish cache
 */
class CPWPurge {
  /**
   * Purges a URL
   *
   * @param \Silex\Application $app
   * A Silex Application
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   * The Symfony Request Object
   *
   */
  public function purge(Application $app, Request $request) {
    $warm = $request->request->get('warm');
    $scheme = $request->request->get('scheme');
    $url = $request->request->get('URL');
    $path = $request->request->get('path');
    $varnish_prefix = $request->request->get('varnish prefix');
    $request_url = "$scheme://$varnish_prefix$url$path";
    $warming_url = "$scheme://$url$path";
    $guzz_request = $app['guzzle']->createRequest('PURGE', $request_url);
    $guzz_request->addHeader('X-Auth-API', '1234567890');
    try {
      $response = $guzz_request->send();
      $return = array(
        'status code' => $response->getStatusCode(),
        'reason' => $response->getReasonPhrase(),
        'url' => $request_url,
        'path' => $request->request->get('path'),
        'warm' => $request->request->get('warm'),
        'varnish prefix' => $request->request->get('varnish prefix'),
        'scheme' => $request->request->get('scheme')
      );
    } catch (BadResponseException $e) {
      $response = array('status code' => $e->getResponse()->getStatusCode(), 'reason' => $e->getResponse()->getReasonPhrase());
      $return = $response;
    }
    if($warm) {
      try {
      $warm_response = $app['guzzle']->get($warming_url)->send();
      $return['warmed'] = array(
        'status code' => $warm_response->getStatusCode(),
        'reason' => $warm_response->getReasonPhrase(),
        'url' => $warming_url,
        'scheme' => $request->request->get('scheme')
      );
      } catch (BadResponseException $e) {
        $response = array('status code' => $e->getResponse()->getStatusCode(), 'reason' => $e->getResponse()->getReasonPhrase());
        $return['warmed'] = $response;
      }
    }

    return $return;
  }
}
