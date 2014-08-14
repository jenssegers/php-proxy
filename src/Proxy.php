<?php
namespace Phpproxy;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Phpproxy\Request\Converter\DefaultRequestConverter;
use Phpproxy\Request\Converter\RequestConverterInterface;
use Phpproxy\Request\Filter\RequestFilterInterface;
use Phpproxy\Response\Converter\DefaultResponseConverter;
use Phpproxy\Response\Converter\ResponseConverterInterface;
use Phpproxy\Response\Filter\ResponseFilterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Proxy
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestConverterInterface
     */
    private $requestConverter;

    /**
     * @var ResponseConverterInterface
     */
    private $responseConverter;

    /**
     * @var RequestFilterInterface[]
     */
    private $requestFilter = [];

    /**
     * @var ResponseFilterInterface[]
     */
    private $responseFilter = [];

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->requestConverter = new DefaultRequestConverter();
        $this->responseConverter = new DefaultResponseConverter();

        $this->client = $client;
    }

    /**
     * @return RequestConverterInterface
     */
    public function getRequestConverter()
    {
        return $this->requestConverter;
    }

    /**
     * @param RequestConverterInterface $requestConverter
     */
    public function setRequestConverter($requestConverter)
    {
        $this->requestConverter = $requestConverter;
    }

    /**
     * @return ResponseConverterInterface
     */
    public function getResponseConverter()
    {
        return $this->responseConverter;
    }

    /**
     * @param ResponseConverterInterface $responseConverter
     */
    public function setResponseConverter($responseConverter)
    {
        $this->responseConverter = $responseConverter;
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
        $guzzleRequest = $this->handleRequest($symfonyRequest, $proxyUrl);

        $guzzleResponse = $this->client->send($guzzleRequest);

        return $this->handleResponse($guzzleResponse);
    }

    /**
     * @param Request $symfonyRequest
     * @param string $proxyUrl
     * @return RequestInterface
     */
    private function handleRequest(Request $symfonyRequest, $proxyUrl)
    {
        $guzzleRequest = $this->requestConverter->convert($symfonyRequest);
        $guzzleRequest->setUrl($proxyUrl);

        foreach ($this->requestFilter as $filter) {
            $filter->filter($symfonyRequest, $guzzleRequest);
        }

        return $guzzleRequest;
    }

    /**
     * @param ResponseInterface $guzzleResponse
     * @return Response
     */
    private function handleResponse(ResponseInterface $guzzleResponse)
    {
        $symfonyResponse = $this->responseConverter->convert($guzzleResponse);

        foreach ($this->responseFilter AS $filter) {
            $filter->filter($guzzleResponse, $symfonyResponse);
        }

        return $symfonyResponse;
    }

}
