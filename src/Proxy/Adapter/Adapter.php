<?php namespace Proxy\Adapter;

use Symfony\Component\HttpFoundation\Request;

interface Adapter {

    /**
     * Send the request and return the response.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  string  $url
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function send(Request $request, $url);

}
