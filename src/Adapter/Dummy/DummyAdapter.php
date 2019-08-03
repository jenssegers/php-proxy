<?php

namespace Proxy\Adapter\Dummy;

use Proxy\Adapter\AdapterInterface;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

class DummyAdapter implements AdapterInterface
{
    /**
     * @inheritdoc
     */
    public function send(RequestInterface $request)
    {
        return new Response($request->getBody(), 200);
    }
}
