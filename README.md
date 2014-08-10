PHP Proxy
=========

This is a HTTP/HTTPS proxy script that forwards requests to a different server and returns the response. The Proxy class uses Symfony request/response objects as input/output, and uses Guzzle to do the actual http request.

Installation
------------

Clone this repository and install the dependencies using `composer install`. Modify and rename `index.example.php` to `index.php`.

Usage
-----

Browse to the proxy script's folder and enjoy the magic.

The `forward` method accepts Symfony 2 request objects or request paths. When no argument is passed, it will automatically create a Symfony request object based on the current request.

The `to` method redirects the previous Symfony request to the url that is passed as an argument. It returns a Symfony response that can then be sent to the browser or inspected.

Example:

```
use Symfony\Component\HttpFoundation\Request;

$request = Request::create(
    '/hello-world',
    'GET',
    array('name' => 'Fabien')
);

$response = Proxy::forward($request)->to('http://myserver.com:8888/site');

$response->send();
```
