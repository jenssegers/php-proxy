<?php

namespace Phpproxy;

use GuzzleHttp\Client;
use Phpproxy\Adapter\GuzzleAdapter;
use Symfony\Component\HttpFoundation\Request;

class Factory
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
     * @param string $url
     * @return Request
     */
    public static function createSimpleRequest($url)
    {
        return Request::create($url, 'GET', $_GET, $_COOKIE, $_FILES, $_SERVER);
    }
}
