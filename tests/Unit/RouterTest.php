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
use Aven\Dispatcher;
use Aven\Filter;
use Aven\Matcher;
use Aven\Request;
use Aven\Resolver;
use Aven\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public $router;

    /**
     * setting up router.
     */
    public function setUp() : void
    {
        $request = new Request();
        $dispatcher = new Dispatcher();
        $filter = new Filter();
        $resolver = new Resolver();
        $matcher = new Matcher($request, $filter);

        $this->router = new Router($dispatcher, $filter, $matcher, $resolver);
    }

    /**
     * test router instance.
     *
     * @return void
     */
    public function testRouterInstance()
    {
        $this->assertInstanceOf(Router::class, $this->router);
    }

    /**
     * test set and get config.
     *
     * @return void
     */
    public function testSetAndGetConfig()
    {
        $this->router->config(['testing' => 'we are testing !']);

        $this->assertEquals($this->router->getConfig('testing'), 'we are testing !');
    }

    /**
     * test getRoutes method.
     *
     * @return void
     */
    public function testGetRoutesIsReturningAnArrayOfRoutes()
    {
        $this->assertIsArray($this->router->getRoutes());
    }
}
