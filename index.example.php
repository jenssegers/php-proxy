<?php
require 'vendor/autoload.php';

$response = Proxy::forward()->to('https://www.reddit.com');

// Output response to browser.
$response->send();
