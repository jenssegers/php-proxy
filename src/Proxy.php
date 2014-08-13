<?php
namespace Phpproxy;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\MessageFactory;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Post\PostFile;
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
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     * @param null|Request|string $request
     */
    public function __construct(ClientInterface $client, $request = null)
    {
        $this->request = ($request instanceof Request) ? $request : Request::create($request, 'GET', $_GET, $_COOKIE, $_FILES, $_SERVER);

        $this->messageFactory = new MessageFactory();
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return Response
     */
    public function to($url)
    {
        $request = $this->convertToGuzzleRequest($this->request);
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
     * @param Request $original
     * @return \GuzzleHttp\Message\RequestInterface
     */
    private function convertToGuzzleRequest(Request $original)
    {
        $request = $this->messageFactory->fromMessage((string) $original);

        $request->removeHeader('host');

        return $request;
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

        return new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders());
    }

}
