<?php
namespace Proxy\Adapter\Dummy;


use Symfony\Component\HttpFoundation\Request;

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
    public function adapter_returns_symfony_response()
    {
        $response = $this->adapter->send(Request::createFromGlobals(), '/');

        $this->assertTrue($response instanceof \Symfony\Component\HttpFoundation\Response);
    }

    /**
     * @test
     */
    public function response_contains_target_url_as_xheader()
    {
        $url = 'http://www.rebuy.de';

        $response = $this->adapter->send(Request::createFromGlobals(), $url);

        $this->assertEquals($url, $response->headers->get("X-Url"));
    }

    /**
     * @test
     */
    public function response_contains_body()
    {
        $content = 'Some awesome content that is passed through.';
        $request = Request::create('/', 'POST', [], [], [], [], $content);

        $response = $this->adapter->send($request, '/');

        $this->assertEquals($content, $response->getContent());
    }
}
