<?php
use Phpproxy\Factory;
use Phpproxy\Response\Filter\JenSeggersEncodingResponseFilter;
use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';

$proxy = Factory::create(Request::createFromGlobals());
$proxy->addResponseFilter(new JenSeggersEncodingResponseFilter());

$response = $proxy->to('http://www.example.com');

// Output response to browser.
var_dump((string) $response);
