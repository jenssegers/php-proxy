<?php
namespace Proxy\Response\Filter;


use Symfony\Component\HttpFoundation\Response;

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
        $response = Response::create('', 200, [RemoveEncodingFilter::TRANSFER_ENCODING => '']);

        $this->filter->filter($response);

        $this->assertFalse($response->headers->has(RemoveEncodingFilter::TRANSFER_ENCODING));
    }

    /**
     * @test
     */
    public function filter_removes_content_encoding()
    {
        $response = Response::create('', 200, [RemoveEncodingFilter::CONTENT_ENCODING => '']);

        $this->filter->filter($response);

        $this->assertFalse($response->headers->has(RemoveEncodingFilter::CONTENT_ENCODING));
    }
}
