<?php

namespace Phpproxy\Response\Converter;


use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

interface ResponseConverterInterface
{

    /**
     * @param ResponseInterface $response
     * @return Response
     */
    public function convert(ResponseInterface $response);

}
