<?php

namespace Proxy\Adapter\Dummy;

use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\ServerRequestFactory;
use PHPUnit\Framework\TestCase;

class DummyAdapterTest extends TestCase
{
    /**
     * @var DummyAdapter
     */
    private $adapter;

    public function setUp(): void
    {
        $this->adapter = new DummyAdapter();
    }

    /**
     * @test
     */
    public function adapter_returns_psr_response()
    {
        $response = $this->adapter->send(ServerRequestFactory::fromGlobals(), '/');

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
