<?php namespace Proxy\Adapter\Dummy;

use Proxy\Adapter\AdapterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

class DummyAdapter implements AdapterInterface {

    /**
     * Send the request and return the response.
     *
     * @param  RequestInterface $request
     * @param  string  $to
     * @return ResponseInterface
     */
    public function send(RequestInterface $request, $to)
    {
        return new Response($request->getBody(), 200, ['X-Url' => $to]);
    }
}
