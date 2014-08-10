<?php
require 'vendor/autoload.php';

$proxy = new Proxy;

$response = $proxy->forward()->to('https://www.reddit.com');

$response->send();
