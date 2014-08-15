<?php namespace Proxy\Request\Filter;

use Symfony\Component\HttpFoundation\Request;

interface RequestFilter {

    /**
     * Process the request.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Request
     */
    public function filter(Request $request);

}
