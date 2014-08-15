<?php
namespace Proxy\Response\Filter;


use Symfony\Component\HttpFoundation\Response;

class RemoveLocationResponseFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RemoveLocationResponseFilter
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new RemoveLocationResponseFilter();
    }

    /**
     * @test
     */
    public function filter_removes_location()
    {
        $response = Response::create('', 200, [RemoveLocationResponseFilter::LOCATION => '/']);

        $this->filter->filterResponse($response);

        $this->assertFalse($response->headers->has(RemoveLocationResponseFilter::LOCATION));
    }

    /**
     * @test
     */
    public function filter_adds_location_as_xheader()
    {
        $url = 'http://www.rebuy.de/';
        $response = Response::create('', 200, [RemoveLocationResponseFilter::LOCATION => $url]);

        $this->filter->filterResponse($response);

        $this->assertEquals($url, $response->headers->get('X-Proxy-Location'));
    }
}
