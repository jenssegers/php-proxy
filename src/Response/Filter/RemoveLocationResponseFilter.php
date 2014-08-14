<?php
namespace Phpproxy\Response\Filter;

use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class RemoveLocationResponseFilter implements ResponseFilterInterface
{

    /**
     * @param ResponseInterface $guzzleResponse
     * @param Response $symfonyResponse
     * @return Response
     */
    public function filter(ResponseInterface $guzzleResponse, Response $symfonyResponse)
    {
        if ($guzzleResponse->hasHeader('location')) {
            $symfonyResponse->headers->set('X-Phpproxy-Location', $guzzleResponse->getHeader('location'));
            $symfonyResponse->headers->remove('location');
        }

        return $symfonyResponse;
    }
}
