<?php

namespace Proxy\Filter;

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

class RemoveLocationFilterTest extends TestCase
{
    /**
     * @var RemoveLocationFilter
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new RemoveLocationFilter();
    }

    /**
     * @test
     */
    public function filter_removes_location()
    {
        $request = new Request();
        $response = new Response('php://memory', 200, [RemoveLocationFilter::LOCATION => 'http://www.example.com']);
        $next = function () use ($response) {
            return $response;
        };

        $response = call_user_func($this->filter, $request, $response, $next);

        $this->assertFalse($response->hasHeader(RemoveLocationFilter::LOCATION));
    }

    /**
     * @test
     */
    public function filter_adds_location_as_xheader()
    {
        $request = new Request();
        $response = new Response('php://memory', 200, [RemoveLocationFilter::LOCATION => 'http://www.example.com']);
        $next = function () use ($response) {
            return $response;
        };

        $response = call_user_func($this->filter, $request, $response, $next);

        $this->assertEquals('http://www.example.com', $response->getHeader('X-Proxy-Location')[0]);
    }
}
