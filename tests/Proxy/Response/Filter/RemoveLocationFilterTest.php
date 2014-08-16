<?php
namespace Proxy\Response\Filter;


use Proxy\Response\Filter\RemoveLocationFilter;
use Symfony\Component\HttpFoundation\Response;

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
        $response = Response::create('', 200, [RemoveLocationFilter::LOCATION => '/']);

        $this->filter->filter($response);

        $this->assertFalse($response->headers->has(RemoveLocationFilter::LOCATION));
    }

    /**
     * @test
     */
    public function filter_adds_location_as_xheader()
    {
        $url = 'http://www.rebuy.de/';
        $response = Response::create('', 200, [RemoveLocationFilter::LOCATION => $url]);

        $this->filter->filter($response);

        $this->assertEquals($url, $response->headers->get('X-Proxy-Location'));
    }
}
