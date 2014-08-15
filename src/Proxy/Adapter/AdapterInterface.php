<?php namespace Proxy\Adapter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface AdapterInterface {

    /**
     * Send the request and return the response.
     *
     * @param  Request $request
     * @param  string  $url
     * @return Response
     */
    public function send(Request $request, $url);

}
