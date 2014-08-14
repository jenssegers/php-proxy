<?php
use Proxy\Adapter\Guzzle\GuzzleFactory;
use Proxy\Response\Filter\RemoveEncodingResponseFilter;
use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';

$proxy = GuzzleFactory::create();
$proxy->addResponseFilter(new RemoveEncodingResponseFilter());

$request = Request::createFromGlobals();
$response = $proxy->send($request, 'http://www.example.com');

// Output response to browser.
var_dump((string) $response);
