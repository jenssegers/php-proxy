<?php
namespace Proxy;

use Proxy\Adapter\AdapterInterface;
use Proxy\Request\Filter\RequestFilterInterface;
use Proxy\Response\Filter\ResponseFilterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Proxy
{
    /**
     * @var Request
     */
    private $symfonyRequest;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var RequestFilterInterface[]
     */
    private $requestFilter = [];

    /**
     * @var ResponseFilterInterface[]
     */
    private $responseFilter = [];

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param RequestFilterInterface[] $requestFilter
     */
    public function setRequestFilter(array $requestFilter)
    {
        $this->requestFilter = $requestFilter;
    }

    /**
     * @param RequestFilterInterface $filter
     */
    public function addRequestFilter(RequestFilterInterface $filter)
    {
        array_push($this->requestFilter, $filter);
    }

    /**
     * @param ResponseFilterInterface[] $responseFilter
     */
    public function setResponseFilter(array $responseFilter)
    {
        $this->responseFilter = $responseFilter;
    }

    /**
     * @param ResponseFilterInterface $filter
     */
    public function addResponseFilter(ResponseFilterInterface $filter)
    {
        array_push($this->responseFilter, $filter);
    }

    /**
     * @param Request $symfonyRequest
     * @return Proxy
     */
    public function forward(Request $symfonyRequest)
    {
        $this->symfonyRequest = $symfonyRequest;

        return $this;
    }

    /**
     * @param string $proxyUrl
     * @return Response
     */
    public function to($proxyUrl)
    {
        $this->applyRequestFilter($this->symfonyRequest);

        $symfonyResponse = $this->adapter->send($this->symfonyRequest, $proxyUrl);

        $this->applyResponseFilter($symfonyResponse);

        return $symfonyResponse;
    }

    /**
     * @param Request $symfonyRequest
     * @return Request
     */
    private function applyRequestFilter(Request $symfonyRequest)
    {
        $applyFilter = function(RequestFilterInterface $filter) use ($symfonyRequest) {
            $filter->filterRequest($symfonyRequest);
        };

        array_map($applyFilter, $this->requestFilter);

        return $symfonyRequest;
    }

    /**
     * @param Response $symfonyResponse
     * @return Response
     */
    private function applyResponseFilter(Response $symfonyResponse)
    {
        $applyFilter = function(ResponseFilterInterface $filter) use ($symfonyResponse) {
            $filter->filterResponse($symfonyResponse);
        };

        array_map($applyFilter, $this->responseFilter);

        return $symfonyResponse;
    }

}
