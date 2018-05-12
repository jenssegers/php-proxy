<?php

namespace Proxy;

use PHPUnit\Framework\TestCase;
use Proxy\Adapter\Dummy\DummyAdapter;
use Proxy\Exception\UnexpectedValueException;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class ProxyTest extends TestCase
{
    /**
     * @var Proxy
     */
    private $proxy;

    public function setUp()
    {
        $this->proxy = new Proxy(new DummyAdapter());
    }

    /**
     * @test
     * @expectedException UnexpectedValueException
     */
    public function to_throws_exception_if_no_request_is_given()
    {
        $this->proxy->to('http://www.example.com');
    }

    /**
     * @test
     */
    public function to_returns_psr_response()
    {
        $response = $this->proxy->forward(ServerRequestFactory::fromGlobals())->to('http://www.example.com');

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }

    /**
     * @test
     */
    public function to_applies_filters()
    {
        $applied = false;

        $this->proxy->forward(ServerRequestFactory::fromGlobals())->filter(function ($request, $response) use (&$applied
        ) {
            $applied = true;
        })->to('http://www.example.com');

        $this->assertTrue($applied);
    }

    /**
     * @test
     */
    public function to_sends_request()
    {
        $request = new Request('http://localhost/path?query=yes', 'GET');
        $url = 'https://www.example.com';

        $adapter = $this->getMockBuilder(DummyAdapter::class)
            ->getMock();

        $verifyParam = $this->callback(function (RequestInterface $request) use ($url) {
            return $request->getUri() == 'https://www.example.com/path?query=yes';
        });

        $adapter->expects($this->once())
            ->method('send')
            ->with($verifyParam)
            ->willReturn(new Response);

        $proxy = new Proxy($adapter);
        $proxy->forward($request)->to($url);
    }

    /**
     * @test
     */
    public function to_sends_request_with_port()
    {
        $request = new Request('http://localhost/path?query=yes', 'GET');
        $url = 'https://www.example.com:3000';

        $adapter = $this->getMockBuilder(DummyAdapter::class)
            ->getMock();

        $verifyParam = $this->callback(function (RequestInterface $request) use ($url) {
            return $request->getUri() == 'https://www.example.com:3000/path?query=yes';
        });

        $adapter->expects($this->once())
            ->method('send')
            ->with($verifyParam)
            ->willReturn(new Response);

        $proxy = new Proxy($adapter);
        $proxy->forward($request)->to($url);
    }

    /**
     * @test
     */
    public function to_sends_request_with_subdirectory()
    {
        $request = new Request('http://localhost/path?query=yes', 'GET');
        $url = 'https://www.example.com/proxy/';

        $adapter = $this->getMockBuilder(DummyAdapter::class)
            ->getMock();

        $verifyParam = $this->callback(function (RequestInterface $request) use ($url) {
            return $request->getUri() == 'https://www.example.com/proxy/path?query=yes';
        });

        $adapter->expects($this->once())
            ->method('send')
            ->with($verifyParam)
            ->willReturn(new Response);

        $proxy = new Proxy($adapter);
        $proxy->forward($request)->to($url);
    }
}
