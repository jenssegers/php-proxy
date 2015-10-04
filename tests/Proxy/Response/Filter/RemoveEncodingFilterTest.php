<?php namespace Proxy\Response\Filter;

use Zend\Diactoros\Response;

class RemoveEncodingFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RemoveEncodingResponseFilter
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
        $response = new Response('php://memory', 200, [RemoveEncodingFilter::TRANSFER_ENCODING => 'foo']);

        $response = $this->filter->filter($response);

        $this->assertFalse($response->hasHeader(RemoveEncodingFilter::TRANSFER_ENCODING));
    }

    /**
     * @test
     */
    public function filter_removes_content_encoding()
    {
        $response = new Response('php://memory', 200, [RemoveEncodingFilter::CONTENT_ENCODING => 'foo']);

        $response = $this->filter->filter($response);

        $this->assertFalse($response->hasHeader(RemoveEncodingFilter::CONTENT_ENCODING));
    }
}
