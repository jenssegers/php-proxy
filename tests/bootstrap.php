<?php

if ( ! is_file($autoloadFile = __DIR__.'/../vendor/autoload.php'))
{
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
}

$loader = require $autoloadFile;

$loader->add('Proxy', __DIR__ . '/Proxy');
