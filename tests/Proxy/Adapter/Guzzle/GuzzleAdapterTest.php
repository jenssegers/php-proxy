<?php namespace Proxy\Proxy\Adapter\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Proxy\Adapter\Guzzle\GuzzleAdapter;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

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
        $mock = new MockHandler([
            $this->createResponse(),
        ]);

        $client = new Client(['handler' => $mock]);

        $this->adapter = new GuzzleAdapter($client);
    }

    /**
     * @test
     */
    public function adapter_returns_psr_response()
    {
        $response = $this->sendRequest();

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }

    /**
     * @test
     */
    public function response_contains_body()
    {
        $response = $this->sendRequest();

        $this->assertEquals($this->body, $response->getBody());
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

        $this->assertEquals('Mock', $response->getHeader('Server')[0]);
    }

    /**
     * @test
     */
    public function adapter_sends_request()
    {
        $clientMock = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $verifyParam = $this->callback(function (Request $request, $options = []) {
            return $request->getUri() == 'http://www.example.com';
        });

        $clientMock->expects($this->once())
            ->method('send')
            ->with($verifyParam)
            ->willReturn($this->createResponse());

        $adapter = new GuzzleAdapter($clientMock);

        $request = new Request('http://localhost', 'GET');

        $adapter->send($request, 'http://www.example.com');
    }

    /**
     * @return Response
     */
    private function sendRequest()
    {
        $request = new Request('http://localhost', 'GET');

        return $this->adapter->send($request, 'http://www.example.com');
    }

    /**
     * @return Response
     */
    private function createResponse()
    {
        return new GuzzleResponse($this->status, $this->headers, $this->body);
    }

}
