<?php

$branch = "Nome";
$url_base = "http://localhost/rppbci";
$branch_description = "";

$hosts = ['localhost']; 
$index = 'rppbci';
$indexAdm = 'rppbciadm';

$tematres_url = "";

/* Background images */
$background_1 = "inc/images/book.jpg";

$debug = true;

/* Load libraries for PHP composer */ 
require (__DIR__.'/../vendor/autoload.php'); 
/* Load Elasticsearch Client */ 
$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build(); 
$fb = new Facebook\Facebook([
  'app_id' => '',
  'app_secret' => '',
  'default_graph_version' => 'v2.10',
  'http_client_handler' => 'stream'
]);

// Sets the default fallback access token so we don't have to pass it to each request
$fb->setDefaultAccessToken('');

$facebook_token = '';

?>