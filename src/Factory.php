<?php

namespace Phpproxy;

use GuzzleHttp\Client;

class Factory
{
    /**
     * @param null|string|Request $forwardTo
     * @return Proxy
     */
    public static function create($forwardTo = null)
    {
        $client = new Client();
        return new Proxy($client, $forwardTo);
    }
}
