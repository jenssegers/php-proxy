<?php

namespace Proxy;

use GuzzleHttp\Exception\ClientException;
use Proxy\Adapter\AdapterInterface;
use Proxy\Exception\UnexpectedValueException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Relay\RelayBuilder;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Uri;

class Proxy
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var callable[]
     */
    protected $filters = [];

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Prepare the proxy to forward a request instance.
     *
     * @param  RequestInterface $request
     * @return $this
     */
    public function forward(RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Forward the request to the target url and return the response.
     *
     * @param  string $target
     * @throws UnexpectedValueException
     * @return ResponseInterface
     */
    public function to($target)
    {
        if ($this->request === null) {
            throw new UnexpectedValueException('Missing request instance.');
        }

        $target = new Uri($target);

        // Overwrite target scheme, host and port.
        $uri = $this->request->getUri()
            ->withScheme($target->getScheme())
            ->withHost($target->getHost())
            ->withPort($target->getPort());

        // Check for subdirectory.
        if ($path = $target->getPath()) {
            $uri = $uri->withPath(rtrim($path, '/') . '/' . ltrim($uri->getPath(), '/'));
        }

        $request = $this->request->withUri($uri);

        $stack = $this->filters;

        $stack[] = function (RequestInterface $request, ResponseInterface $response, callable $next) {
            try {
                $response = $this->adapter->send($request);
            } catch (ClientException $ex) {
                $response = $ex->getResponse();
            }

            return $next($request, $response);
        };

        $relay = (new RelayBuilder)->newInstance($stack);

        return $relay($request, new Response);
    }

    /**
     * Add a filter middleware.
     *
     * @param  callable $callable
     * @return $this
     */
    public function filter(callable $callable)
    {
        $this->filters[] = $callable;

        return $this;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
