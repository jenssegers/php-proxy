<?php
namespace Phpproxy;

use Phpproxy\Request\Filter\RequestFilterInterface;
use Phpproxy\Response\Filter\ResponseFilterInterface;
use Phpproxy\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Proxy
{
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
     * @param $requestFilter RequestFilterInterface[]
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
     * @param $responseFilter ResponseFilterInterface[]
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
     * @param string $proxyUrl
     * @return Response
     */
    public function send(Request $symfonyRequest, $proxyUrl)
    {
        $this->applyRequestFilter($symfonyRequest);

        $symfonyResponse = $this->adapter->send($symfonyRequest, $proxyUrl);

        $this->applyResponseFilter($symfonyResponse);

        return $symfonyResponse;


    }

    /**
     * @param Request $symfonyRequest
     * @return Request
     */
    private function applyRequestFilter(Request $symfonyRequest)
    {
        foreach ($this->requestFilter as $filter) {
            $filter->filter($symfonyRequest);
        }

        return $symfonyRequest;
    }

    /**
     * @param Response $symfonyResponse
     * @return Response
     */
    private function applyResponseFilter(Response $symfonyResponse)
    {
        foreach ($this->responseFilter AS $filter) {
            $filter->filter($symfonyResponse);
        }

        return $symfonyResponse;
    }

}
