<?php namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

class RemoveLocationFilter implements ResponseFilterInterface {

    const LOCATION = 'location';

    /**
     * Process the response.
     *
     * @param  Response $response
     * @return Response
     */
    public function filter(Response $response)
    {
        if ($response->headers->has(self::LOCATION))
        {
            $response->headers->set('X-Proxy-Location', $response->headers->get(self::LOCATION));

            $response->headers->remove(self::LOCATION);
        }

        return $response;
    }
}
