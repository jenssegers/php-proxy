<?php namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

interface ResponseFilter
{
    /**
     * Process the response.
     *
     * @param  Symfony\Component\HttpFoundation\Response $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function filter(Response $response);
}
