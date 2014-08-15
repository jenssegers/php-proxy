<?php namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

class RemoveEncodingFilter implements ResponseFilter {

    /**
     * Process the response.
     *
     * @param  Symfony\Component\HttpFoundation\Response $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function filter(Response $response)
    {
        $response->headers->remove('transfer-encoding');

        $response->headers->remove('content-encoding');

        return $response;
    }

}
