<?php namespace Proxy\Request\Filter;

use Psr\Http\Message\RequestInterface;

interface RequestFilterInterface {

    /**
     * Process the request.
     *
     * @param  RequestInterface $request
     * @return RequestInterface
     */
    public function filter(RequestInterface $request);

}
