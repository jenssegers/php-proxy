<?php

namespace Phpproxy;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class Factory
{
    /**
     * @return Proxy
     */
    public static function create()
    {
        $client = new Client();
        return new Proxy($client);
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
