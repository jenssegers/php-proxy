<?php

namespace Phpproxy;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class Factory
{
    /**
     * @param null|Request|string $request
     * @return Proxy
     */
    public static function create($request = null)
    {
        $client = new Client();
        return new Proxy($client, $request);
    }
}
