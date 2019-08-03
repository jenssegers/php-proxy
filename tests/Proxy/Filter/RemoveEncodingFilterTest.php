<?php

namespace Proxy\Filter;

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

class RemoveEncodingFilterTest extends TestCase
{
    /**
     * @var RemoveEncodingFilter
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new RemoveEncodingFilter();
    }

    /**
     * @test
     */
    public function filter_removes_transfer_encoding()
    {
        $request = new Request();
        $response = new Response('php://memory', 200, [RemoveEncodingFilter::TRANSFER_ENCODING => 'foo']);
        $next = function () use ($response) {
            return $response;
        };

        $response = call_user_func($this->filter, $request, $response, $next);

        $this->assertFalse($response->hasHeader(RemoveEncodingFilter::TRANSFER_ENCODING));
    }

    /**
     * @test
     */
    public function filter_removes_content_encoding()
    {
        $request = new Request();
        $response = new Response('php://memory', 200, [RemoveEncodingFilter::TRANSFER_ENCODING => 'foo']);
        $next = function ($request, $response) {
            return $response;
        };

        $response = call_user_func($this->filter, $request, $response, $next);

        $this->assertFalse($response->hasHeader(RemoveEncodingFilter::CONTENT_ENCODING));
    }
}
