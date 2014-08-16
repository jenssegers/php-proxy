<?php namespace Proxy\Response\Filter;

use Symfony\Component\HttpFoundation\Response;

class RemoveEncodingFilter implements ResponseFilterInterface {

    const TRANSFER_ENCODING = 'transfer-encoding';

    const CONTENT_ENCODING = 'content-encoding';

    /**
     * Process the response.
     *
     * @param  Response $response
     * @return Response
     */
    public function filter(Response $response)
    {
        $response->headers->remove(self::TRANSFER_ENCODING);

        $response->headers->remove(self::CONTENT_ENCODING);

        return $response;
    }

}
