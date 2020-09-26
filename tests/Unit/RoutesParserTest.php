<?php

namespace Tests\Unit;

/*
 * This file is a part of aven
 *
 * @package     Aven
 * @version     1.0.0
 * @author      Lotfio Lakehal <contact@lotfio.net>
 * @copyright   Lotfio Lakehal 2019
 * @license     MIT
 * @link        https://github.com/lotfio/aven
 *
 */

use PHPUnit\Framework\TestCase;
use Aven\RoutesParser;

class RoutesParserTest extends TestCase
{
    /**
     * example route
     *
     * @var array
     */
    protected $routes = [
        [
            'method'    => 'GET',
            'uri'       => '',
            'action'    => '',
            'regex'     => '',
            'name'      => '',
            'group'     => '',
            'namespace' => ''
        ]
    ];

    /**
     * test parse route uri
     *
     * @return void
     */
    public function testParseUri()
    {
        $parser = new RoutesParser;

        $this->routes[0]['uri'] = 'test';
        $parser->parse($this->routes);
        $this->assertSame('~^/test$~', $this->routes[0]['uri']);

        $this->routes[0]['uri'] = '/test/';
        $parser->parse($this->routes);
        $this->assertSame('~^/test$~', $this->routes[0]['uri']);
    }

    /**
     * test parse route action
     *
     * @return void
     */
    public function testParseAction()
    {
        $parser = new RoutesParser;

        $this->routes[0]['namespace'] = 'Test\\';
        $this->routes[0]['action'] = 'TestClass';

        $parser->parse($this->routes);
        $this->assertSame('Test\TestClass', $this->routes[0]['action']);
    }
    
    /**
     * test parse predefined
     *
     * @return void
     */
    public function testParsePredefined()
    {
        $parser = new RoutesParser;

        $this->routes[0]['uri'] = '/test/(:int)';
        $parser->parse($this->routes);
        $this->assertSame('~^/test/(\d+)$~', $this->routes[0]['uri']);

        $this->routes[0]['uri'] = '/test/(:str)';
        $parser->parse($this->routes);
        $this->assertSame('~^/test/(\w+)$~', $this->routes[0]['uri']);

        $this->routes[0]['uri'] = '/test/(:alpha)';
        $parser->parse($this->routes);
        $this->assertSame('~^/test/([A-z]+)$~', $this->routes[0]['uri']);

        $this->routes[0]['uri'] = '/test/(:id*)';
        $parser->parse($this->routes);
        $this->assertSame('~^/test/?(\d*)$~', $this->routes[0]['uri']);

        $this->routes[0]['uri'] = '/test/(:id?)';
        $parser->parse($this->routes);
        $this->assertSame('~^/test/(\d?)$~', $this->routes[0]['uri']);
    }

    /**
     * test parse optional
     *
     * @return void
     */
    public function testParseOptional()
    {
        $parser = new RoutesParser;
        $this->routes[0]['uri'] = '/test/(:id*)';
        $parser->parse($this->routes);
        $this->assertSame('~^/test/?(\d*)$~', $this->routes[0]['uri']);
    }

    /**
     * test parse user defined regex
     *
     * @return void
     */
    public function testParseRegex()
    {
        $parser = new RoutesParser;

        $this->routes[0]['uri'] = '/test/(:id)';
        $this->routes[0]['regex'] = ["(:id)" => "[custom-pattern]"];

        $parser->parse($this->routes);
        $this->assertSame('~^/test/([custom-pattern])$~', $this->routes[0]['uri']);
    }
}