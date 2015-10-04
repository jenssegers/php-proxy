<?php namespace Proxy\Adapter\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
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
     * @param  string  $to
     * @return ResponseInterface
     */
    public function send(RequestInterface $request, $to)
    {
        $request = $request->withUri(new Uri($to));

        return $this->client->send($request);
    }
}
