<?php namespace Proxy\Adapter\Dummy;

use Zend\Diactoros\ServerRequestFactory;

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
}
