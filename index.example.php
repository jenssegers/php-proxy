<?php
require 'vendor/autoload.php';

$response = Proxy::forward()->to('https://www.reddit.com');

$response->send();
