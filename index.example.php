<?php
use Phpproxy\Factory;

require 'vendor/autoload.php';


$response = Factory::create()->to('https://www.reddit.com');

// Output response to browser.
$response->send();
