<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

// GET Province

// GET Cost
$app->get(
    '/cost/:origin/:destination/:weight/:kurir',
    function ($origin, $destination, $weight, $kurir) use ($app) {
      // is cURL installed yet?
      if (!function_exists('curl_init')){
          die('Sorry cURL is not installed!');
      }
      $url = "http://ibacor.com/api/ongkir?service=".$kurir."&dari=".$origin."&ke=".$destination."&berat=".$weight;
      $ch = curl_init();
   
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      $output = curl_exec($ch);
      curl_close($ch);
   
      echo $output;
    }
);

$app->get(
    '/track/:pengirim/:resi',
    function ($pengirim, $resi) use ($app) {
      // is cURL installed yet?
      if (!function_exists('curl_init')){
          die('Sorry cURL is not installed!');
      }
      $url = "http://ibacor.com/api/cek-resi?pengirim=".$pengirim."&resi=".$resi."";
      $ch = curl_init();
   
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      $output = curl_exec($ch);
      curl_close($ch);
   
      echo $output;
    }
);

// GET Venues
$app->get(
    '/venues/:type/:courier/:lat/:lng',
    function ($type,$courier,$lat,$lng) use ($app) {
      require_once("Slim/Helper/FoursquareApi.php");
      // Set your client key and secret
      $client_key = "WV124K331SFEO2MBDJNHYIR4IS4ZWMF4LX43HR14YIQ1EODL";
      $client_secret = "1EPTKZLEGIVGUJBQDT2M5P2LCRAVQFQJIYTQYFYDNAB0HBHS";

      $foursquare = new FoursquareApi($client_key,$client_secret);
      
      // Prepare parameters
      $params = array("ll"=>"$lat,$lng","query"=>"$courier");
      
      // Perform a request to a public resource
      $response = $foursquare->GetPublic("venues/".$type,$params);
      $app->response()->header("Content-Type", "application/json");
      echo $response;
    }
);

// GET Venue Detail
$app->get(
    '/venue/:id',
    function ($id) use ($app) {
      require_once("Slim/Helper/FoursquareApi.php");
      // Set your client key and secret
      $client_key = "WV124K331SFEO2MBDJNHYIR4IS4ZWMF4LX43HR14YIQ1EODL";
      $client_secret = "1EPTKZLEGIVGUJBQDT2M5P2LCRAVQFQJIYTQYFYDNAB0HBHS";

      $foursquare = new FoursquareApi($client_key,$client_secret);
      
      // Perform a request to a public resource
      $response = $foursquare->GetPublic("venues/".$id);
      $app->response()->header("Content-Type", "application/json");
      echo $response;
    }
);

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
