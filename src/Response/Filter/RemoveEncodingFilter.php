<?php namespace Proxy\Response\Filter;

use Psr\Http\Message\ResponseInterface;

class RemoveEncodingFilter implements ResponseFilterInterface {

    const TRANSFER_ENCODING = 'transfer-encoding';

    const CONTENT_ENCODING = 'content-encoding';

    /**
     * Process the response.
     *
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    public function filter(ResponseInterface $response)
    {
        return $response
            ->withoutHeader(self::TRANSFER_ENCODING)
            ->withoutHeader(self::CONTENT_ENCODING);
    }

}
