# PHP Proxy

This is a HTTP/HTTPS proxy script that forwards requests to a different server and returns the response. The Proxy class uses Symfony request/response objects as input/output, and uses Guzzle to do the actual http request.

## Installation

Add `"phpproxy/proxy": "~3"` to your composer require section.  
To use the provided `GuzzleAdapter` a Guzzle installation is required. If none present add `"guzzlehttp/guzzle": "~4.1.7"` as dependency.    


## Demo / Testing

Clone this repository and install the dependencies using `composer install --dev`. Run `index.example.php` in your browser or console.

## Usage


Browse to the proxy script's folder and enjoy the magic.

The `send` method redirects the given Symfony request to the url that is passed as second argument. It returns a Symfony response that can then be sent to the browser or inspected.

Example with GuzzleAdapter:

```
use Symfony\Component\HttpFoundation\Request;
use Proxy\Adapter\Guzzle\GuzzleFactory;

// Create custom request.
$request = Request::create(
    '/hello-world',
    'GET',
    array('name' => 'Fabien')
);

// Get proxy response.
$response = GuzzleFactory::create()->send($request, 'https://www.reddit.com')

// Output response to browser.
$response->send();
```
