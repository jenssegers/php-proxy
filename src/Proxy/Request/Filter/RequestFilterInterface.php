<?php namespace Proxy\Request\Filter;

use Symfony\Component\HttpFoundation\Request;

interface RequestFilterInterface {

    /**
     * Process the request.
     *
     * @param  Request $request
     * @return Request
     */
    public function filter(Request $request);

}
