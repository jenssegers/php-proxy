<?php namespace Proxy\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Message\MessageFactory;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GuzzleAdapter implements Adapter {

    /**
     * The Guzzle client instance.
     *
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * The Guzzle message factory instance.
     *
     * @var GuzzleHttp\Message\MessageFactory
     */
    protected $messageFactory;

    /**
     * Construct a Guzzle based HTTP adapter.
     *
     * @param GuzzleHttp\Client $client
     * @param GuzzleHttp\Message\MessageFactory $messageFactory
     */
    public function __construct(Client $client = null, MessageFactory $messageFactory = null)
    {
        $this->client = $client ?: new Client;

        $this->messageFactory = $messageFactory ?: new MessageFactory;
    }

    /**
     * Send the request and return the response.
     *
     * @param  Symfony\Component\HttpFoundation\Request $symfonyRequest
     * @param  string  $url
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function send(Request $symfonyRequest, $url)
    {
        $guzzleRequest = $this->convertRequest($symfonyRequest);

        $guzzleRequest->setUrl($url);

        $guzzleResponse = $this->client->send($guzzleRequest);

        return $this->convertResponse($guzzleResponse);
    }

    /**
     * Convert the Symfony request to a Guzzle request.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return GuzzleHttp\Message\RequestInterface
     */
    protected function convertRequest(Request $request)
    {
        return $this->messageFactory->fromMessage((string) $request);
    }

    /**
     * Conver the Guzzle response to a Symfony response.
     *
     * @param  GuzzleHttp\Message\ResponseInterface $response
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function convertResponse(ResponseInterface $response)
    {
        return new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders());
    }
}
