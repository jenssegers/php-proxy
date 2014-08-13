<?php
namespace Phpproxy;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\ResponseInterface;
use Phpproxy\Request\Converter\DefaultRequestConverter;
use Phpproxy\Request\Converter\RequestConverterInterface;
use Phpproxy\Response\Converter\DefaultResponseConverter;
use Phpproxy\Response\Converter\ResponseConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Proxy
{
    /**
     * @var bool
     */
    private $rewriteLocation = false;

    /**
     * @var Request
     */
    private $request;

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
     * @param ClientInterface $client
     * @param Request|string $request
     */
    public function __construct(ClientInterface $client, $request)
    {
        $this->request = ($request instanceof Request) ? $request : Request::create($request, 'GET', $_GET, $_COOKIE, $_FILES, $_SERVER);

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
     * @param string $url
     * @return Response
     */
    public function to($url)
    {
        $request = $this->requestConverter->convert($this->request);
        $request->setUrl($url);

        $response = $this->client->send($request);

        return $this->convertToSymfonyResponse($response);
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function rewriteLocation($bool = true)
    {
        $this->rewriteLocation = $bool;

        return $this;
    }

    /**
     * @param ResponseInterface $response
     * @return Response
     */
    private function convertToSymfonyResponse(ResponseInterface $response)
    {
        $response->removeHeader('transfer-encoding');
        $response->removeHeader('content-encoding');

        if ($this->rewriteLocation and $response->hasHeader('location')) {
            $location = parse_url($response->getHeader('location'));

            $url = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');

            if (isset($location['path'])) {
                $url .= $location['path'];
            }

            if (isset($location['query'])) {
                $url .= '?' . $location['query'];
            }

            $response->setHeader('location', $url);
        }

        return $this->responseConverter->convert($response);
    }

}
