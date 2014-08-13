<?php

namespace Phpproxy\Request\Converter;


use GuzzleHttp\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

interface RequestConverterInterface
{

    /**
     * @param Request $request
     * @return RequestInterface
     */
    public function convert(Request $request);
}
