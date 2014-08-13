<?php
namespace Phpproxy\Response\Filter;

use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

interface ResponseFilterInterface
{
    /**
     * @param ResponseInterface $guzzleResponse
     * @param Response $symfonyResponse
     * @return Response
     */
    public function filter(ResponseInterface $guzzleResponse, Response $symfonyResponse);
}
