<?php namespace Proxy\Adapter\Guzzle;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Proxy\Adapter\AdapterInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
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
     * @param  Request $request
     * @param  string  $url
     * @return Response
     */
    public function send(Request $request, $url)
    {
        $guzzleRequest = $this->convertRequest($request)->withUri(new Uri($url));

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
        return new GuzzleRequest(
            $request->getMethod(),
            $request->getRequestUri(),
            $request->headers->all(),
            $request->getContent()
        );
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
