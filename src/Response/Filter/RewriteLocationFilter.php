<?php namespace Proxy\Response\Filter;

use Psr\Http\Message\ResponseInterface;

class RewriteLocationFilter implements ResponseFilterInterface {

    const LOCATION = 'location';

    /**
     * Process the response.
     *
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    public function filter(ResponseInterface $response)
    {
        if ($response->hasHeader(self::LOCATION))
        {
            $original = parse_url($response->getHeader(self::LOCATION));

            $target = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');

            if (isset($original['path']))  $target .= $original['path'];

            if (isset($original['query'])) $target .= '?' . $original['query'];

            $response = $response
                ->withHeader('X-Proxy-Location', $response->getHeader(self::LOCATION))
                ->withHeader(self::LOCATION, $target);
        }

        return $response;
    }
}
