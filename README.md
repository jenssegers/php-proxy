# PHP Proxy

[![Build Status](http://img.shields.io/travis/jenssegers/php-proxy.svg)](https://travis-ci.org/jenssegers/php-proxy) [![Coverage Status](http://img.shields.io/coveralls/jenssegers/php-proxy.svg)](https://coveralls.io/r/jenssegers/php-proxy?branch=master)

This is a HTTP/HTTPS proxy script that forwards requests to a different server and returns the response. The Proxy class uses PSR7 request/response objects as input/output, and uses Guzzle to do the actual HTTP request.

## Installation

Install using composer:

```
composer require jenssegers/proxy
```

## Examples

The following example creates a request object, based on the current browser request, and forwards it to `example.com`. The `RemoveEncodingFilter` removes the encoding headers from the original response so that the current webserver can set these correctly.

```php
use Proxy\Proxy;
use Proxy\Adapter\Guzzle\GuzzleAdapter;
use Zend\Diactoros\ServerRequestFactory;

require 'vendor/autoload.php';

// Create a PSR7 request based on the current browser request.
$request = ServerRequestFactory::fromGlobals();

// Create a guzzle client
$guzzle = new GuzzleHttp\Client();

// Create the proxy instance
$proxy = new Proxy(new GuzzleAdapter($guzzle));

// Forward the request and get the response.
$response = $proxy->forward($request)->to('http://example.com');

// Output response to the browser.
(new Zend\Diactoros\Response\SapiEmitter)->emit($response);
```
