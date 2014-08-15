<?php
namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

class RemoveEncodingResponseFilter implements ResponseFilterInterface
{

    /**
     * @param Response $symfonyResponse
     * @return Response
     */
    public function filterResponse(Response $symfonyResponse)
    {
        $symfonyResponse->headers->remove('transfer-encoding');
        $symfonyResponse->headers->remove('content-encoding');

        return $symfonyResponse;
    }
}
