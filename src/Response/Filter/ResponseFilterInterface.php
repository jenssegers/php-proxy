<?php namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

interface ResponseFilterInterface {

    /**
     * Process the response.
     *
     * @param  Response $response
     * @return Response
     */
    public function filter(Response $response);

}
