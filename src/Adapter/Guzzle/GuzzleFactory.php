<?php
namespace Proxy\Adapter\Guzzle;

use GuzzleHttp\Client;
use Proxy\Proxy;
use Symfony\Component\HttpFoundation\Request;

class GuzzleFactory
{
    /**
     * @return Proxy
     */
    public static function create()
    {
        $client = new Client();
        $adapter = new GuzzleAdapter($client);

        return new Proxy($adapter);
    }

    /**
     * @param $symfonyRequest
     * @return Proxy
     */
    public static function forward($symfonyRequest)
    {
        return self::create()->forward($symfonyRequest);
    }

    /**
     * @param string $url
     * @return Request
     */
    public static function createSimpleRequest($url)
    {
        return Request::create($url, 'GET', $_GET, $_COOKIE, $_FILES, $_SERVER);
    }
}
