<?php
namespace Phpproxy\Response\Filter;

use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class RemoveLocationResponseFilter implements ResponseFilterInterface
{

    private static $HEADER_NAME = 'location';

    /**
     * @param Response $symfonyResponse
     * @return Response
     */
    public function filter(Response $symfonyResponse)
    {
        if ($symfonyResponse->hasHeader(self::$HEADER_NAME)) {
            $symfonyResponse->headers->set('X-Phpproxy-Location', $symfonyResponse->headers->get(self::$HEADER_NAME));
            $symfonyResponse->headers->remove(self::$HEADER_NAME);
        }

        return $symfonyResponse;
    }
}
