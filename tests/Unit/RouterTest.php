<?php

/**
 * Aven          Robust PHP Router 
 *
 * @package      Aven
 * @author       Lotfio Lakehal <lotfiolakehal@gmail.com>
 * @copyright    2016 Lotfio Lakehal
 * @license      MIT
 * @link         https://github.com/lotfio/aven
 *
 */

use PHPUnit\Framework\TestCase;
use Aven\{Router, Dispatcher, Filter, Matcher, Request, Resolver};

class RouterTest extends TestCase
{
    public $router;

    /**
     * setting up router
     */
    public function setUp()
    {
        $request        = new Request;
        $dispatcher     = new Dispatcher;
        $filter         = new Filter;
        $resolver       = new Resolver;
        $matcher        = new Matcher($request, $filter);

        $this->router = new Router($dispatcher, $filter, $matcher, $resolver);
    }

    /**
     * test router instance 
     * 
     * @return void
     */
    public function testRouterInstance()
    {
        $this->assertInstanceOf(Router::class, $this->router);
    }

    /**
     * test set and get config
     * 
     * @return void
     */
    public function testSetAndGetConfig()
    {
        $this->router->config(["testing" => "we are testing !"]);

        $this->assertEquals($this->router->getConfig('testing'), "we are testing !");
    }

    /**
     * test getRoutes method
     * 
     * @return void
     */
    public function testGetRoutesIsReturningAnArrayOfRoutes()
    {
        $this->assertInternalType('array', $this->router->getRoutes());
    }
}