<?php namespace Proxy\Response\Filter;

use Psr\Http\Message\ResponseInterface;

class RemoveLocationFilter implements ResponseFilterInterface {

    const LOCATION = 'location';

    /**
     * Process the response.
     *
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    public function filter(ResponseInterface $response)
    {
        if ($response->hasHeader(self::LOCATION))
        {
            $response = $response
                ->withHeader('X-Proxy-Location', $response->getHeader(self::LOCATION))
                ->withoutHeader(self::LOCATION);
        }

        return $response;
    }
}
