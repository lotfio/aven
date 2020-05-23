<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Aven\RoutesTable;

class RoutesTableTest extends TestCase
{
    /**
     * test add route to routing table
     *
     * @return void
     */
    public function testAddRouteMethodWrongUriParameters()
    {
        $table = new RoutesTable; 
        $this->expectException(\Aven\Exceptions\RoutesTableException::class);
        $table->addRoute('POST', '/hello~', 'controller');

    }

    /**
     * test add new route method
     *
     * @return void
     */
    public function testAddRouteMethodAddNewsRoute()
    {
        $table = new RoutesTable; 
        $table->addRoute('POST', '/hello', 'controller');
        $this->assertEmpty($table->getRoutes());
        $table->init();
        $route = $table->getRoutes()[0];
        $this->assertCount(7, $route);
        $this->assertSame('/hello', $route['REGEX_URI']);
        $this->assertSame('POST', $route['REQUEST_METHOD']);
        $this->assertSame('controller', $route['ACTION']);
        $this->assertEmpty($route['PARAMS_REGEX']);
        $this->assertEmpty($route['NAME']);
        $this->assertSame('/', $route['GROUP']);
    }

    /**
     * test reg emethod
     *
     * @return void
     */
    public function testRegexMethod()
    {
        $table = new RoutesTable; 
        $table->addRoute('POST', '/hello', 'controller');
        $table->regex(array(":id" => "[A-Z]+"));
        $table->init();
        $this->assertCount(1, $table->getRoutes()[0]['PARAMS_REGEX']);
    }

    /**
     * test route name method 
     *
     * @return void
     */
    public function testNameMethod()
    {
        $table = new RoutesTable; 
        $table->addRoute('POST', '/hello', 'controller');
        $table->name("sweet");
        $table->init();
        $this->assertSame("sweet", $table->getRoutes()[0]['NAME']);
    }

    /**
     * test get routes
     *
     * @return void
     */
    public function testGetRoutesMethod()
    {
        $table = new RoutesTable; 
        $table->addRoute('POST', '/hello', 'controller');
        $table->init();
        $this->assertCount(7, $table->getRoutes()[0]);
    }
}