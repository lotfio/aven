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
use Aven\RoutesTable;

class RoutesTableTest extends TestCase
{
    /**
     * test add route
     *
     * @return void
     */
    public function testAddRoute()
    {
        $table = new RoutesTable;
        $table->add('GET', '/test', function(){ return 'from test';});
        
        $this->assertContains('GET', $table->getRoutes()[0]);
        $this->assertContains('/test', $table->getRoutes()[0]);
        $this->assertSame('from test', $table->getRoutes()[0]['action']());
    }

    /**
     * test add route regex 
     *
     * @return void
     */
    public function testAddRouteRegex()
    {
        $table = new RoutesTable;
        $table->add('GET', '/test', function(){ return 'from test';})
        ->regex([':id' => '[\d+]']);

        $this->assertSame([':id' => '[\d+]'], $table->getRoutes()[0]['regex']);
    }

    /**
     * test add route name
     *
     * @return void
     */
    public function testAddRouteName()
    {
        $table = new RoutesTable;
        $table->add('GET', '/test', function(){ return 'from test';})
        ->name('named route');

        $this->assertSame('named route', $table->getRoutes()[0]['name']);
    }

    /**
     * test add route group
     *
     * @return void
     */
    public function testAddRouteGroup()
    {
        $table = new RoutesTable;
        $table->setGroup("/test/route/group");
        $table->add('GET', '/test', function(){ return 'from test';});
        
        $this->assertSame('/test/route/group', $table->getRoutes()[0]['group']);

        $table->setGroup("/////test//////route/////group////");
        $table->add('GET', '/test', function(){ return 'from test';});

        $this->assertSame('/test/route/group', $table->getRoutes()[0]['group']);
    }

    /**
     * test unset route group
     *
     * @return void
     */
    public function testAddRouteUnsetGroup()
    {
        $table = new RoutesTable;
        $table->setGroup("/test/route/group");
        $table->unsetGroup("/group");
        $table->add('GET', '/test', function(){ return 'from test';});

        $this->assertSame('/test/route', $table->getRoutes()[0]['group']);
    }

    /**
     * test add route namespace
     *
     * @return void
     */
    public function testAddRouteNamespace()
    {
        $table = new RoutesTable;
        $table->setNamespace("My\\Class");
        $table->add('GET', '/test', function(){ return 'from test';});

        $this->assertSame('\\My\\Class\\', $table->getRoutes()[0]['namespace']);
    }

    /**
     * test unset route namespace
     *
     * @return void
     */
    public function testAddRouteUnsetNamespace()
    {
        $table = new RoutesTable;
        $table->setNamespace("My\\Class");
        $table->unsetNamespace("My");
        $table->add('GET', '/test', function(){ return 'from test';});

        $this->assertSame('\\Class\\', $table->getRoutes()[0]['namespace']);
    }
}