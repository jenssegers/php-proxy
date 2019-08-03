<?php

namespace Proxy\Filter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RemoveLocationFilter implements FilterInterface
{
    const LOCATION = 'location';

    /**
     * @inheritdoc
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $response = $next($request, $response);

        if ($response->hasHeader(self::LOCATION)) {
            $response = $response
                ->withHeader('X-Proxy-Location', $response->getHeader(self::LOCATION))
                ->withoutHeader(self::LOCATION);
        }

        return $response;
    }
}
