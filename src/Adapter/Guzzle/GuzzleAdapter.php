<?php namespace Proxy\Adapter\Guzzle;

use GuzzleHttp\Client;
use Proxy\Adapter\AdapterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleAdapter implements AdapterInterface {

    /**
     * The Guzzle client instance.
     *
     * @var Client
     */
    protected $client;

    /**
     * Construct a Guzzle based HTTP adapter.
     *
     * @param Client $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client;
    }

    /**
     * Send the request and return the response.
     *
     * @param  RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        return $this->client->send($request);
    }
}
