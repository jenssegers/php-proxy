<?php
use Phpproxy\Factory;
use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';

$response = Factory::create(Request::createFromGlobals())->to('https://www.reddit.com');

// Output response to browser.
$response->send();
