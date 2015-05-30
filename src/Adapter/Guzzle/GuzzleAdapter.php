<?php namespace Proxy\Adapter\Guzzle;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Proxy\Adapter\AdapterInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

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
     * @param  SymfonyRequest $request
     * @param  string  $to
     * @return SymfonyResponse
     */
    public function send(SymfonyRequest $request, $to)
    {
        $guzzleRequest = $this->convertRequest($request)->withUri(new Uri($to));

        $guzzleResponse = $this->client->send($guzzleRequest);

        return $this->convertResponse($guzzleResponse);
    }

    /**
     * Convert the Symfony request to a Guzzle request.
     *
     * @param  SymfonyRequest $request
     * @return RequestInterface
     */
    protected function convertRequest(SymfonyRequest $request)
    {
        return (new DiactorosFactory())->createRequest($request);
    }

    /**
     * Conver the Guzzle response to a Symfony response.
     *
     * @param  ResponseInterface $response
     * @return SymfonyResponse
     */
    protected function convertResponse(ResponseInterface $response)
    {
        return (new HttpFoundationFactory())->createResponse($response);
    }
}
