<?php

namespace Proxy\Filter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RewriteLocationFilter implements FilterInterface
{
    const LOCATION = 'location';

    /**
     * @inheritdoc
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $response = $next($request, $response);

        if ($response->hasHeader(self::LOCATION)) {
            $original = parse_url($response->getHeader(self::LOCATION));

            $target = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');

            if (isset($original['path'])) {
                $target .= $original['path'];
            }

            if (isset($original['query'])) {
                $target .= '?' . $original['query'];
            }

            $response = $response
                ->withHeader('X-Proxy-Location', $response->getHeader(self::LOCATION))
                ->withHeader(self::LOCATION, $target);
        }

        return $response;
    }
}
