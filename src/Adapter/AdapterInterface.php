<?php namespace Proxy\Adapter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface AdapterInterface {

    /**
     * Send the request and return the response.
     *
     * @param  RequestInterface $request
     * @param  string  $url
     * @return ResponseInterface
     */
    public function send(RequestInterface $request, $url);

}
