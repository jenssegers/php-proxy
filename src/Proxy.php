<?php

use GuzzleHttp\Client;
use GuzzleHttp\Post\PostFile;
use GuzzleHttp\Message\MessageFactory;
use GuzzleHttp\Message\Response as GuzzleRequest;
use GuzzleHttp\Message\Response as GuzzleResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Proxy {

    protected $request;

    protected $rewriteLocation = false;

    public function forward($request = null)
    {
        if ( ! $request instanceof Request)
        {
            $path = $request;
            $request = Request::createFromGlobals();

            if (is_string($path))
            {
                $request->server->set('REQUEST_URI', '/' . ltrim($path, '/'));
            }
        }

        $this->request = $request;

        return $this;
    }

    public function to($url)
    {
        $request = $this->convertRequest($this->request);

        $url = parse_url($url);

        if (isset($url['host']))   $request->setHost($url['host']);
        if (isset($url['port']))   $request->setPort($url['port']);
        if (isset($url['scheme'])) $request->setScheme($url['scheme']);
        if (isset($url['path']))   $request->setPath($url['path'] . $guzzleRequest->getPath());

        $client = new Client;

        $response = $client->send($request);

        return $this->convertResponse($response);
    }

    public function convertRequest(Request $original)
    {
        $factory = new MessageFactory;

        $request = $factory->fromMessage((string) $original);

        $request->removeHeader('host');

        if ($original->files->count())
        {
            $request->removeHeader('content-type');

            foreach ($original->files->all() as $key => $file)
            {
                $request->getBody()->addFile(new PostFile($key, fopen($file->getRealPath(), 'r'), $file->getClientOriginalName()));
            }
        }

        return $request;
    }

    protected function convertResponse(GuzzleResponse $response)
    {
        $response->removeHeader('transfer-encoding');
        $response->removeHeader('content-encoding');

        if ($this->rewriteLocation and $response->hasHeader('location'))
        {
            $location = parse_url($response->getHeader('location'));

            $url = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');

            if (isset($location['path']))  $url .= $location['path'];
            if (isset($location['query'])) $url .= '?' . $location['query'];

            $response->setHeader('location', $url);
        }

        $response = new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders());

        return $response;
    }

    public function rewriteLocation($bool = true)
    {
        $this->rewriteLocation = $bool;

        return $this;
    }

}
