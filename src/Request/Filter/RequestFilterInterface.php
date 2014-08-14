<?php
namespace Phpproxy\Request\Filter;

use GuzzleHttp\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

interface RequestFilterInterface
{
    /**
     * @param Request $symfonyRequest
     * @return Request
     */
    public function filter(Request $symfonyRequest);
}
