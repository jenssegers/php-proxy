<?php namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

class RewriteLocationFilter implements ResponseFilterInterface {

    /**
     * Process the response.
     *
     * @param  Response $response
     * @return Response
     */
    public function filter(Response $response)
    {
        if ($response->headers->has('location'))
        {
            $original = parse_url($response->headers->get('location'));

            $target = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');

            if (isset($original['path']))  $target .= $original['path'];

            if (isset($original['query'])) $target .= '?' . $original['query'];

            $response->headers->set('X-Proxy-Location', $response->headers->get('location'));

            $response->headers->set('location', $target);
        }

        return $response;
    }
}
