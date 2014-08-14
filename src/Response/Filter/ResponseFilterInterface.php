<?php
namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

interface ResponseFilterInterface
{
    /**
     * @param Response $symfonyResponse
     * @return Response
     */
    public function filter(Response $symfonyResponse);
}
