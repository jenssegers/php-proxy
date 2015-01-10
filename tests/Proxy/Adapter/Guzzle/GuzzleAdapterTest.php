<?php
namespace Proxy\Proxy\Adapter\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Ring\Client\MockHandler;
use Proxy\Adapter\Guzzle\GuzzleAdapter;
use Symfony\Component\HttpFoundation\Request;

class GuzzleAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GuzzleAdapter
     */
    private $adapter;

    /**
     * @var array
     */
    private $headers = ['Server' => 'Mock'];

    /**
     * @var int
     */
    private $status = 200;

    /**
     * @var string
     */
    private $body = 'Totally awesome response body';

    public function setUp()
    {
        $response = $this->createResponse();

        $mock = new MockHandler([
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $response->getBody(),
        ]);

        $client = new Client(['handler' => $mock]);

        $this->adapter = new GuzzleAdapter($client);
    }

    /**
     * @test
     */
    public function adapter_returns_symfony_response()
    {
        $response = $this->sendRequest();

        $this->assertTrue($response instanceof \Symfony\Component\HttpFoundation\Response);
    }

    /**
     * @test
     */
    public function response_contains_body()
    {
        $response = $this->sendRequest();

        $this->assertEquals($this->body, $response->getContent());
    }

    /**
     * @test
     */
    public function response_contains_statuscode()
    {
        $response = $this->sendRequest();

        $this->assertEquals($this->status, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function response_contains_header()
    {
        $response = $this->sendRequest();

        $this->assertEquals('Mock', $response->headers->get('Server'));
    }

    /**
     * @test
     */
    public function adapter_sends_request()
    {
        $clientMock = $this->getMockBuilder('\GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $verifyParam = $this->callback(function(\GuzzleHttp\Message\Request $request) {
            return $request->getUrl() == 'http://www.example.com';
        });

        $clientMock->expects($this->once())
            ->method('send')
            ->with($verifyParam)
            ->willReturn($this->createResponse());

        $adapter = new GuzzleAdapter($clientMock);

        $adapter->send(Request::createFromGlobals(), 'http://www.example.com');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function sendRequest()
    {
        $request = Request::createFromGlobals();
        return $this->adapter->send($request, 'http://www.example.com');
    }

    /**
     * @return Response
     */
    private function createResponse()
    {
        $body = Stream::factory($this->body);
        return new Response($this->status, $this->headers, $body);
    }

}
