<?php namespace Proxy\Adapter\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Message\MessageFactory;
use GuzzleHttp\Message\MessageFactoryInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Proxy\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GuzzleAdapter implements AdapterInterface {

    /**
     * The Guzzle client instance.
     *
     * @var Client
     */
    protected $client;

    /**
     * The Guzzle message factory instance.
     *
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * Construct a Guzzle based HTTP adapter.
     *
     * @param Client $client
     * @param \GuzzleHttp\Message\MessageFactoryInterface $messageFactory
     */
    public function __construct(Client $client = null, MessageFactoryInterface $messageFactory = null)
    {
        $this->client = $client ? : new Client;

        $this->messageFactory = $messageFactory ? : new MessageFactory;
    }

    /**
     * Send the request and return the response.
     *
     * @param  Request $request
     * @param  string  $url
     * @return Response
     */
    public function send(Request $request, $url)
    {
        $guzzleRequest = $this->convertRequest($request);

        $guzzleRequest->setUrl($url);

        $guzzleResponse = $this->client->send($guzzleRequest);

        return $this->convertResponse($guzzleResponse);
    }

    /**
     * Convert the Symfony request to a Guzzle request.
     *
     * @param  Request $request
     * @return RequestInterface
     */
    protected function convertRequest(Request $request)
    {
        return $this->messageFactory->fromMessage((string) $request);
    }

    /**
     * Conver the Guzzle response to a Symfony response.
     *
     * @param  ResponseInterface $response
     * @return Response
     */
    protected function convertResponse(ResponseInterface $response)
    {
        return new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders());
    }
}
