<?php

namespace Proxy\Filter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FilterInterface
{
    /**
     * Apply filter to request and/or response.
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next);
}
