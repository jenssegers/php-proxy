<?php namespace Proxy\Adapter\Dummy;

use Zend\Diactoros\Request;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Stream;

class DummyAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DummyAdapter
     */
    private $adapter;

    public function setUp()
    {
        $this->adapter = new DummyAdapter();
    }

    /**
     * @test
     */
    public function adapter_returns_psr_response()
    {
        $response = $this->adapter->send(ServerRequestFactory::fromGlobals(), '/');

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }

    /**
     * @test
     */
    public function response_contains_target_url_as_xheader()
    {
        $url = 'http://www.example.com';

        $response = $this->adapter->send(ServerRequestFactory::fromGlobals(), $url);

        $this->assertEquals($url, $response->getHeader('X-Url')[0]);
    }
}
