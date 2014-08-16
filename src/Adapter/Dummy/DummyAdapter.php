<?php namespace Proxy\Adapter\Dummy;

use Proxy\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DummyAdapter implements AdapterInterface {

    /**
     * Send the request and return the response.
     *
     * @param  Request $request
     * @param  string  $url
     * @return Response
     */
    public function send(Request $request, $url)
    {
        $response = new Response($request->getContent(), 200, ['X-Url' => $url]);

        $response->prepare($request);

        return $response;
    }
}
