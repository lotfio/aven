<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Aven\RoutesFilter;

class RoutesFilterTest extends TestCase
{
    private $filter; 
    private $route;

    public function setUp() : void
    {
        $this->routes = array(
            array(
                'REQUEST_URI'    => '',
                'REGEX_URI'      => '/',
                'REQUEST_METHOD' => 'GET',
                'PARAMS_REGEX'   => [],
                'ACTION'         => '\Tests\Unit\Stbs\TestController@testValid',
                'NAME'           => 'test',
                'GROUP'          => '',
            )
        );
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->filter = new RoutesFilter;
    }

    /**
     * test apply regex
     *
     * @return void
     */
    public function testApplyRegexUriDefault()
    {
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/$~', $this->routes[0]['REGEX_URI']);
    }

    /**
     * test apply reg ex to uri
     *
     * @return void
     */
    public function testApplyRegexUriRoute()
    {
        $this->routes[0]['REGEX_URI'] = '/test';
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/test$~', $this->routes[0]['REGEX_URI']);
    }

    /**
     * test default regex params
     *
     * @return void
     */
    public function testApplyRegexUriDefaultFilters()
    {
        $this->routes[0]['REGEX_URI'] = '/test/(:id)';
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/test\/(\d+)$~', $this->routes[0]['REGEX_URI']);

        $this->routes[0]['REGEX_URI'] = '/test/(:id?)';
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/test\/(\d?)$~', $this->routes[0]['REGEX_URI']);

        $this->routes[0]['REGEX_URI'] = '/test/(:id*)';
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/test\/(\d*)$~', $this->routes[0]['REGEX_URI']);

        $this->routes[0]['REGEX_URI'] = '/test/(:int)';
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/test\/(\d+)$~', $this->routes[0]['REGEX_URI']);

        $this->routes[0]['REGEX_URI'] = '/test/(:integer)';
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/test\/(\d+)$~', $this->routes[0]['REGEX_URI']);

        $this->routes[0]['REGEX_URI'] = '/test/(:num)';
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/test\/(\d+)$~', $this->routes[0]['REGEX_URI']);

        $this->routes[0]['REGEX_URI'] = '/test/(:numeric)';
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/test\/(\d+)$~', $this->routes[0]['REGEX_URI']);

        $this->routes[0]['REGEX_URI'] = '/test/{:int}';
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/test\/(\d+)$~', $this->routes[0]['REGEX_URI']);
    }

    /**
     * test custom regex params
     *
     * @return void
     */
    public function testApplyRegexUriCustomFilters()
    {
        $this->routes[0]['REGEX_URI']    = '/test/{:placeholder}';
        $this->routes[0]['PARAMS_REGEX'] = array(":placeholder" => '[ABC]');
        $this->filter->applyFilters($this->routes);
        $this->assertSame('~^\/test\/([ABC])$~', $this->routes[0]['REGEX_URI']);
    }
}