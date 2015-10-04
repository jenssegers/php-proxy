<?php namespace Proxy\Response\Filter;

use Psr\Http\Message\ResponseInterface;

interface ResponseFilterInterface {

    /**
     * Process the response.
     *
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    public function filter(ResponseInterface $response);

}
