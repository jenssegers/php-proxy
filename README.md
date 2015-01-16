# PHP Proxy

[![Build Status](http://img.shields.io/travis/jenssegers/php-proxy.svg)](https://travis-ci.org/jenssegers/php-proxy) [![Coverage Status](http://img.shields.io/coveralls/jenssegers/php-proxy.svg)](https://coveralls.io/r/jenssegers/php-proxy?branch=master)

This is a HTTP/HTTPS proxy script that forwards requests to a different server and returns the response. The Proxy class uses Symfony request/response objects as input/output, and uses Guzzle to do the actual http request.

## Installation

Install using composer:

```
composer require jenssegers/proxy
```

## Examples

The following example creates a request object, based on the current browser request, and forwards it to `example.com`. The `RemoveEncodingFilter` removes the encoding headers from the original response so that the current webserver can set these correctly.

```php
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
$response = $proxy->forward($request)->to('http://example.com');

// Output response to the browser.
$response->send();
```

You can also proxy "custom" requests:

```php
use Proxy\Factory;
use Proxy\Response\Filter\RemoveEncodingFilter;
use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';

// Create the proxy factory.
$proxy = Factory::create();

// Add a response filter that removes the encoding headers.
$proxy->addResponseFilter(new RemoveEncodingFilter());

// Create a custom request.
$request = Request::create(
    '/hello-world',
    'GET',
    array('name' => 'Fabien')
);

// Forward the request and get the response.
$response = $proxy->forward($request)->to('http://example.com');

// Output response to the browser.
$response->send();
```

The following example uses a shortcut that is built into the factory:

```php
use Proxy\Factory;
use Proxy\Response\Filter\RemoveEncodingFilter;
use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';

// Create a Symfony request based on the current browser request.
$request = Request::createFromGlobals();

// Forward the request and get the response.
$response = Factory::forward($request)->to('http://example.com');

// Output response to the browser.
$response->send();
```

Or if you prefer not to use the factory:

```php
use Proxy\Proxy;
use Proxy\Adapter\Guzzle\GuzzleAdapter;
use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';

// Create a Symfony request based on the current browser request.
$request = Request::createFromGlobals();

// Create a guzzle client
$guzzle = new GuzzleHttp\Client;

// Create the proxy instance
$proxy = new Proxy(new GuzzleAdapter($guzzle));

// Forward the request and get the response.
$response = $proxy->forward($request)->to('http://example.com');

// Output response to the browser.
$response->send();
```
