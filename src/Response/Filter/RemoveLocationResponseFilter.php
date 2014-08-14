<?php
namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

class RemoveLocationResponseFilter implements ResponseFilterInterface
{

    private static $HEADER_NAME = 'location';

    /**
     * @param Response $symfonyResponse
     * @return Response
     */
    public function filterResponse(Response $symfonyResponse)
    {
        if ($symfonyResponse->headers->has(self::$HEADER_NAME)) {
            $symfonyResponse->headers->set('X-Proxy-Location', $symfonyResponse->headers->get(self::$HEADER_NAME));
            $symfonyResponse->headers->remove(self::$HEADER_NAME);
        }

        return $symfonyResponse;
    }
}
