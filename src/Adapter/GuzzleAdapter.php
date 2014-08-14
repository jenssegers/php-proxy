<?php
namespace Proxy\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Message\MessageFactory;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GuzzleAdapter implements AdapterInterface
{
    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->messageFactory = new MessageFactory();
    }

    /**
     * @param Request $symfonyRequest
     * @param string $url
     * @return Response
     */
    public function send(Request $symfonyRequest, $url)
    {
        $guzzleRequest = $this->convertRequest($symfonyRequest);
        $guzzleRequest->setUrl($url);

        $guzzleResponse = $this->client->send($guzzleRequest);

        return $this->convertResponse($guzzleResponse);
    }

    /**
     * @param Request $request
     * @return RequestInterface
     */
    private function convertRequest(Request $request)
    {
        return $this->messageFactory->fromMessage((string)$request);
    }

    /**
     * @param ResponseInterface $response
     * @return Response
     */
    private function convertResponse(ResponseInterface $response)
    {
        return new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders());
    }
}
