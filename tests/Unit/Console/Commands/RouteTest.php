<?php

namespace Tests\Unit\Console\Commands;

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

use Conso\Input;
use Conso\Output;
use OoFile\Conf;
use PHPUnit\Framework\TestCase;
use Aven\Console\Commands\Route;

class RouteTest extends TestCase
{
    private $routeCommand;

    /**
     *
     */
    public function setUp() : void
    {
        Conf::add(dirname(__DIR__, 4).'/src/Aven/conf');
        $this->routeCommand = new Route(new Input, new Output);
    }

    /**
     * test set command is creating an array of commands
     *
     * @return void
     */
    public function testCacheLocationIsReturningAvalidString()
    {
        $this->assertTrue(is_dir($this->routeCommand->cacheLocation()));
    }

    /** 
     *  test list routes method
     * @return void
     */
    public function testListRoutes()
    {
        $this->expectException(\Conso\Exceptions\RunTimeException::class);
        $this->routeCommand->listRoutes();
    }
}