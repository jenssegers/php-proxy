<?php

namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

class RemoveLocationFilter implements ResponseFilter
{
    const LOCATION = 'location';

    /**
     * @param Response $symfonyResponse
     * @return Response
     */
    public function filter(Response $symfonyResponse)
    {
        if ($symfonyResponse->headers->has(self::LOCATION)) {
            $symfonyResponse->headers->set('X-Proxy-Location', $symfonyResponse->headers->get(self::LOCATION));
            $symfonyResponse->headers->remove(self::LOCATION);
        }

        return $symfonyResponse;
    }
}
