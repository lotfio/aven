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
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    /**
     * @throws \Aven\NotFoundException
     *
     * @return void
     */
    public function testDispatchRoutesThrowInvalidArgumentException()
    {
        $dispatcher = new \Aven\Dispatcher();

        $this->expectException(InvalidArgumentException::class);
        $dispatcher->dispatch('', []);
    }

    /**
     * @throws \Aven\NotFoundException
     *
     * @return void
     */
    public function testDispatchRoutesIsReturningValidObject()
    {
        $dispatcher = new \Aven\Dispatcher();

        $route = $dispatcher->dispatch('get', [
            'index/action/{id}',
            function () {
            },
        ]);

        $this->assertInstanceOf(\stdClass::class, $route);
    }
}
