<?php

namespace Phpproxy;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class Factory
{
    /**
     * @param Request|string $request
     * @return Proxy
     */
    public static function create($request)
    {
        $client = new Client();
        return new Proxy($client, $request);
    }
}
