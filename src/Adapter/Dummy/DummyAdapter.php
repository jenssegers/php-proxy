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
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        return new Response($request->getBody(), 200);
    }
}
