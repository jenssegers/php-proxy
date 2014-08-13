<?php
namespace Phpproxy\Response\Filter;


use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class RemoveEncodingResponseFilter implements ResponseFilterInterface
{

    /**
     * @param ResponseInterface $guzzleResponse
     * @param Response $symfonyResponse
     * @return Response
     */
    public function filter(ResponseInterface $guzzleResponse, Response $symfonyResponse)
    {
        $symfonyResponse->headers->remove('transfer-encoding');
        $symfonyResponse->headers->remove('content-encoding');

        return $symfonyResponse;
    }
}
