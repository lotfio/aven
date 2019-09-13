<?php

/**
 * Aven          Robust PHP Router.
 *
 * @author       Lotfio Lakehal <lotfiolakehal@gmail.com>
 * @copyright    2016 Lotfio Lakehal
 * @license      MIT
 *
 * @link         https://github.com/lotfio/aven
 */
use Aven\Filter;
use Aven\Request;
use PHPUnit\Framework\TestCase;

class MatcherTest extends TestCase
{
    public function testMatchRouteIsmatching()
    {
        $request = $this->createMock(Request::class);
        $filter = $this->createMock(Filter::class);

        $matcher = new \Aven\Matcher($request, $filter);

        $filter->method('filterRegEx')->will($this->returnValue(true));

        $request->expects($this->any())->method('uri')->willReturn('aven');

        $request->expects($this->any())->method('isValidHttpMethod')->willReturn(true);

        $routes = [(object) [

        'pattern' => '#^aven$#',
        'method'  => 'GET',
        'action'  => 'IndexController@index',
        'params'  => [],
        'filters' => 0,

        ]];

        $this->assertInstanceOf(stdClass::class, $matcher->match($routes));
    }

    public function testMatchRouteIsNotMAtching()
    {
        $request = $this->createMock(Request::class);
        $filter = $this->createMock(Filter::class);

        $matcher = new \Aven\Matcher($request, $filter);

        $filter->method('filterRegEx')->will($this->returnValue(true));

        $request->expects($this->any())->method('uri')->willReturn('aven');

        $request->expects($this->any())->method('isValidHttpMethod')->willReturn(true);

        $request->expects($this->any())->method('notFoundRoute')
        ->will($this->throwException(new \Exception('not found route !')));

        $routes = [(object) [

        'pattern' => '#^$#',
        'method'  => 'GET',
        'action'  => 'IndexController@index',
        'params'  => [],
        'filters' => 0,

        ]];

        $this->expectException(Exception::class);
        $matcher->match($routes);
    }
}
