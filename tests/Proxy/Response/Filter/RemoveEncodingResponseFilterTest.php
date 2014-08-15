<?php
namespace Proxy\Response\Filter;


use Symfony\Component\HttpFoundation\Response;

class RemoveEncodingResponseFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RemoveEncodingResponseFilter
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new RemoveEncodingResponseFilter();
    }

    /**
     * @test
     */
    public function filter_removes_transfer_encoding()
    {
        $response = Response::create('', 200, [RemoveEncodingResponseFilter::TRANSFER_ENCODING => '']);

        $this->filter->filterResponse($response);

        $this->assertFalse($response->headers->has(RemoveEncodingResponseFilter::TRANSFER_ENCODING));
    }

    /**
     * @test
     */
    public function filter_removes_content_encoding()
    {
        $response = Response::create('', 200, [RemoveEncodingResponseFilter::CONTENT_ENCODING => '']);

        $this->filter->filterResponse($response);

        $this->assertFalse($response->headers->has(RemoveEncodingResponseFilter::CONTENT_ENCODING));
    }
}
