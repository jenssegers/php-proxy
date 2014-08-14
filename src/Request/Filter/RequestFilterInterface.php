<?php
namespace Proxy\Request\Filter;

use Symfony\Component\HttpFoundation\Request;

interface RequestFilterInterface
{
    /**
     * @param Request $symfonyRequest
     * @return Request
     */
    public function filter(Request $symfonyRequest);
}
