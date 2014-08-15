<?php

use Proxy\Factory;
use Proxy\Response\Filter\RemoveEncodingFilter;
use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';

// Create the proxy factory.
$proxy = Factory::create();

// Add a response filter that removes the encoding headers.
$proxy->addResponseFilter(new RemoveEncodingFilter());

// Create a Symfony request based on the current browser request.
$request = Request::createFromGlobals();

// Forward the request and get the response.
$response = $proxy->forward($request)->to('http://www.example.com');

// Output response to the browser.
$response->send();
