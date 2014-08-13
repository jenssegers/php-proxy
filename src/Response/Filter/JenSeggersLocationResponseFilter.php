<?php
namespace Phpproxy\Response\Filter;

use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class JenSeggersLocationResponseFilter implements ResponseFilterInterface
{

    /**
     * @param ResponseInterface $guzzleResponse
     * @param Response $symfonyResponse
     * @return Response
     */
    public function filter(ResponseInterface $guzzleResponse, Response $symfonyResponse)
    {
        if ($guzzleResponse->hasHeader('location')) {
            $location = parse_url($guzzleResponse->getHeader('location'));

            $url = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');

            if (isset($location['path'])) {
                $url .= $location['path'];
            }

            if (isset($location['query'])) {
                $url .= '?' . $location['query'];
            }

            $symfonyResponse->headers->set('Location', $url);
        }

        return $symfonyResponse;
    }
}
