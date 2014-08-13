<?php
require 'vendor/autoload.php';

$response = Phpproxy\Proxy::forward()->to('https://www.reddit.com');

// Output response to browser.
$response->send();
