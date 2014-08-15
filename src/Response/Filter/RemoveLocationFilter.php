<?php namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

class RemoveLocationFilter implements ResponseFilterInterface {

    /**
     * Process the response.
     *
     * @param  Response $request
     * @return Response
     */
    public function filter(Response $response)
    {
        if ($response->headers->has('location'))
        {
            $response->headers->set('X-Proxy-Location', $response->headers->get('location'));

            $response->headers->remove('location');
        }

        return $response;
    }

}
