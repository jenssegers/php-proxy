<?php
namespace Phpproxy;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\MessageFactory;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Post\PostFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Proxy
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var bool
     */
    private $rewriteLocation = false;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param null $forwardTo
     * @return static
     */
    public static function forward($forwardTo = null)
    {
        $instance = new static;
        $request = ($forwardTo instanceof Request) ? $forwardTo : Request::createFromGlobals();

        if (is_string($forwardTo)) {
            $uri = '/' . ltrim($forwardTo, '/');
            $request->server->set('REQUEST_URI', $uri);
        }

        $instance->messageFactory = new MessageFactory();
        $instance->request = $request;
        $instance->client = new Client();

        return $instance;
    }

    /**
     * @param $url
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

        if ($original->files->count()) {
            $request->removeHeader('content-type');

            foreach ($original->files->all() as $key => $file) {
                $request->getBody()->addFile(new PostFile($key, fopen($file->getRealPath(), 'r'), $file->getClientOriginalName()));
            }
        }

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
