<?php namespace Proxy\Response\Filter;

use Zend\Diactoros\Response;

class RemoveLocationFilterTest extends \PHPUnit_Framework_TestCase
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
        $response = new Response('php://memory', 200, [RemoveLocationFilter::LOCATION => '/']);

        $response = $this->filter->filter($response);

        $this->assertFalse($response->hasHeader(RemoveLocationFilter::LOCATION));
    }

    /**
     * @test
     */
    public function filter_adds_location_as_xheader()
    {
        $url = 'http://www.example.com';

        $response = new Response('php://memory', 200, [RemoveLocationFilter::LOCATION => $url]);

        $response = $this->filter->filter($response);

        $this->assertEquals($url, $response->getHeader('X-Proxy-Location')[0]);
    }
}
