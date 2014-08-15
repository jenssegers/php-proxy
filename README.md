# PHP Proxy

This is a HTTP/HTTPS proxy script that forwards requests to a different server and returns the response. The Proxy class uses Symfony request/response objects as input/output, and uses Guzzle to do the actual http request.

## Installation

Add the package to your `composer.json` and run `composer update`.

{
    "require": {
        "jenssegers/proxy": "2.*"
    }
}

## Examples

The following example creates a request object, based on the current browser request, and forwards it to `example.com`. The `RemoveEncodingFilter` removes the encoding headers from the original response so that the current webserver can set these correctly.

```
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

The following example uses a shortcut that is built into the factory:

```
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
