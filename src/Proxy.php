<?php namespace Proxy;

use Closure;
use Proxy\Adapter\AdapterInterface;
use Proxy\Exception\UnexpectedValueException;
use Proxy\Request\Filter\RequestFilterInterface;
use Proxy\Response\Filter\ResponseFilterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * The registered request filters.
     *
     * @var RequestFilterInterface[]
     */
    protected $requestFilters = [];

    /**
     * The registered response filters.
     *
     * @var ResponseFilterInterface[]
     */
    protected $responseFilters = [];

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

        $this->request = $this->applyRequestFilter($this->request);

        $response = $this->adapter->send($this->request, $target);

        $response = $this->applyResponseFilter($response);

        return $response;
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

    /**
     * Overwrite the request filters array.
     *
     * @param array $filters
     */
    public function setRequestFilters(array $filters)
    {
        $this->requestFilters = $filters;
    }

    /**
     * Register a request filter.
     *
     * @param mixed $filter
     */
    public function addRequestFilter($filter)
    {
        array_push($this->requestFilters, $filter);
    }

    /**
     * Overwrite the response filters array.
     *
     * @param array $filters
     */
    public function setResponseFilters(array $filters)
    {
        $this->responseFilters = $filters;
    }

    /**
     * Register a response filter.
     *
     * @param mixed $filter
     */
    public function addResponseFilter($filter)
    {
        array_push($this->responseFilters, $filter);
    }

    /**
     * Apply request filters to the request instance.
     *
     * @param  RequestInterface $request
     * @return RequestInterface
     */
    protected function applyRequestFilter(RequestInterface $request)
    {
        foreach ($this->requestFilters as $filter)
        {
            if ($filter instanceof RequestFilterInterface)
            {
                $request = $filter->filter($request) ?: $request;
            }
            elseif ($filter instanceof Closure)
            {
                $request = $filter($request) ?: $request;
            }
        }

        return $request;
    }

    /**
     * Apply response filters to the response instance.
     *
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    protected function applyResponseFilter(ResponseInterface $response)
    {
        foreach ($this->responseFilters as $filter)
        {
            if ($filter instanceof ResponseFilterInterface)
            {
                $response = $filter->filter($response) ?: $response;
            }
            elseif ($filter instanceof Closure)
            {
                $response = $filter($response) ?: $response;
            }
        }

        return $response;
    }

}
