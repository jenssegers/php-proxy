<?php namespace Proxy;

use Closure;
use Proxy\Adapter\AdapterInterface;
use Proxy\Exception\UnexpectedValueException;
use Proxy\Request\Filter\RequestFilterInterface;
use Proxy\Response\Filter\ResponseFilterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Proxy {

    /**
     * The Request instance.
     *
     * @var Request
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
    protected $requestFilters = array();

    /**
     * The registered response filters.
     *
     * @var ResponseFilterInterface[]
     */
    protected $responseFilters = array();

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
     * @param  Request $request
     * @return $this
     */
    public function forward(Request $request)
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
     * @return Request
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
     * @param  Request $request
     * @return Request
     */
    protected function applyRequestFilter(Request $request)
    {
        $callback = function($filter) use ($request)
        {
            if ($filter instanceof RequestFilterInterface)
            {
                return $filter->filter($request);
            }
            else if ($filter instanceof Closure)
            {
                return $filter($request);
            }
        };

        array_map($callback, $this->requestFilters);

        return $request;
    }

    /**
     * Apply response filters to the response instance.
     *
     * @param  Response $response
     * @return Response
     */
    protected function applyResponseFilter(Response $response)
    {
        $callback = function($filter) use ($response)
        {
            if ($filter instanceof ResponseFilterInterface)
            {
                return $filter->filter($response);
            }
            else if ($filter instanceof Closure)
            {
                return $filter($response);
            }
        };

        array_map($callback, $this->responseFilters);

        return $response;
    }

}
