<?php namespace Proxy;

use Proxy\Adapter\AdapterInterface;
use Proxy\Exception\UnexpectedValueException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Relay\RelayBuilder;
use Zend\Diactoros\Response;

class Proxy {

    /**
     * The Request instance.
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * The adapter instance.
     *
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Middleware filters.
     *
     * @var callable[]
     */
    protected $filters = [];

    /**
     * Construct a Proxy instance.
     *
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
     * @return Response
     */
    public function to($target)
    {
        if (is_null($this->request))
        {
            throw new UnexpectedValueException('Missing request instance.');
        }

        $uri = $this->request->getUri()
            ->withScheme(parse_url($target, PHP_URL_SCHEME))
            ->withHost(parse_url($target, PHP_URL_HOST));

        $request = $this->request->withUri($uri);

        $stack = $this->filters;

        $stack[] = function (RequestInterface $request, ResponseInterface $response, callable $next) use ($target)
        {
            $response = $this->adapter->send($request, $target);

            return $next($request, $response);
        };

        $relay = (new RelayBuilder)->newInstance($stack);

        return $relay($request, new Response);
    }

    /**
     * Add filter middleware.
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
     * Get the request instance.
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

}
