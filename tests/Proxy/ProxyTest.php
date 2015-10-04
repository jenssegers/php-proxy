<?php namespace Proxy;

use Proxy\Adapter\Dummy\DummyAdapter;
use Proxy\Exception\UnexpectedValueException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class ProxyTest extends \PHPUnit_Framework_TestCase
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
        $this->proxy->to('/');
    }

    /**
     * @test
     */
    public function to_returns_psr_response()
    {
        $response = $this->proxy->forward(ServerRequestFactory::fromGlobals())->to('/');

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }

    /**
     * @test
     */
    public function to_applies_request_filters()
    {
        $filter = $this->getMockBuilder('Proxy\Request\Filter\RequestFilterInterface')
            ->getMock();

        $filter->expects($this->once())
            ->method('filter');

        $this->proxy->addRequestFilter($filter);

        $this->proxy->forward(ServerRequestFactory::fromGlobals())->to('/');
    }

    /**
     * @test
     */
    public function to_applies_response_filters_from_set()
    {
        $filter = $this->getMockBuilder('Proxy\Response\Filter\ResponseFilterInterface')
            ->getMock();

        $filter->expects($this->once())
            ->method('filter');

        $this->proxy->setResponseFilters([$filter]);

        $this->proxy->forward(ServerRequestFactory::fromGlobals())->to('/');
    }

    /**
     * @test
     */
    public function to_applies_request_filters_from_set()
    {
        $filter = $this->getMockBuilder('Proxy\Request\Filter\RequestFilterInterface')
            ->getMock();

        $filter->expects($this->once())
            ->method('filter');

        $this->proxy->setRequestFilters([$filter]);

        $this->proxy->forward(ServerRequestFactory::fromGlobals())->to('/');
    }

    /**
     * @test
     */
    public function to_applies_response_filters()
    {
        $filter = $this->getMockBuilder('Proxy\Response\Filter\ResponseFilterInterface')
            ->getMock();

        $filter->expects($this->once())
            ->method('filter');

        $this->proxy->addResponseFilter($filter);

        $this->proxy->forward(ServerRequestFactory::fromGlobals())->to('/');
    }

    /**
     * @test
     */
    public function to_sends_request()
    {
        $request = ServerRequestFactory::fromGlobals();
        $url = 'http://www.example.com';

        $adapter = $this->getMockBuilder('Proxy\Adapter\Dummy\DummyAdapter')
            ->getMock();

        $adapter->expects($this->once())
            ->method('send')
            ->with($request, $url)
            ->willReturn(new Response);

        $proxy = new Proxy($adapter);
        $proxy->forward($request)->to($url);
    }

    /**
     * @test
     */
    public function to_applies_request_filter_closure()
    {
        $executed = false;

        $this->proxy->addRequestFilter(function (RequestInterface $request) use (&$executed)
        {
            $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
            $executed = true;
        });

        $this->proxy->forward(ServerRequestFactory::fromGlobals())->to('/');

        $this->assertTrue($executed);
    }

    /**
     * @test
     */
    public function to_applies_response_filter_closure()
    {
        $executed = false;

        $this->proxy->addResponseFilter(function (ResponseInterface $response) use (&$executed)
        {
            $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
            $executed = true;
        });

        $this->proxy->forward(ServerRequestFactory::fromGlobals())->to('/');

        $this->assertTrue($executed);
    }

    /**
     * @test
     */
    public function to_request_filter_returns_new_request()
    {
        $replace = new Request;

        $this->proxy->addRequestFilter(function (RequestInterface $request) use ($replace)
        {
            return $replace;
        });

        $this->proxy->forward(ServerRequestFactory::fromGlobals())->to('/');

        $this->assertEquals($this->proxy->getRequest(), $replace);
    }

}
