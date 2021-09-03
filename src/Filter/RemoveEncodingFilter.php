<?php

namespace Proxy\Filter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RemoveEncodingFilter implements FilterInterface
{
    const TRANSFER_ENCODING = 'transfer-encoding';
    const CONTENT_ENCODING = 'content-encoding';

    /**
     * @inheritdoc
     */
    public function __invoke(RequestInterface $request, callable $next): ResponseInterface
    {
        $response = $next($request);

        return $response
            ->withoutHeader(self::TRANSFER_ENCODING)
            ->withoutHeader(self::CONTENT_ENCODING);
    }
}
