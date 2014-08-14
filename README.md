PHP Proxy
=========

This is a HTTP/HTTPS proxy script that forwards requests to a different server and returns the response. The Proxy class uses Symfony request/response objects as input/output, and uses Guzzle to do the actual http request.

Installation
------------

Clone this repository and install the dependencies using `composer install`. Modify and rename `index.example.php` to `index.php`.

Usage
-----

Browse to the proxy script's folder and enjoy the magic.

The `send` method redirects the given Symfony request to the url that is passed as second argument. It returns a Symfony response that can then be sent to the browser or inspected.

Example:

```
use Symfony\Component\HttpFoundation\Request;
use Proxy\Factory;

// Create custom request.
$request = Request::create(
    '/hello-world',
    'GET',
    array('name' => 'Fabien')
);

// Get proxy response.
$response = Factory::create()->send($request, 'https://www.reddit.com')

// Output response to browser.
$response->send();
```
