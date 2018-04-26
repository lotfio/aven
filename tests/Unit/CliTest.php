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

class CliTest extends TestCase
{
    private $cli;

    /**
     * 
     */
    public function setUp()
    {
        $this->cli = new \Aven\Cli;
    }

    /**
     * test set command is creating an array of commands
     * 
     * @return void
     */
    public function testsetCliCommandsIsSteeingCommands()
    {
        $this->assertInternalType('array', $this->cli->commands);
    }

    /**
     * test write line method
     * @return void
     */
    public function testWriteLineMethod()
    {
        $cli = $this->createMock(\Aven\Cli::class);

        $cli->method('writeLn')->will($this->returnArgument(0));
        
        $this->expectOutputString('from console');
        echo $cli->writeLn('from console');

    }

    /**
     *  test wants
     * @return void
     */
    public function testWanstIsCallinTheRightMethod()
    {
        $cli = $this->createMock(\Aven\Cli::class);

        $cli->method('cache')->willReturn('cache');
        $cli->method('clearCache')->willReturn('clearcache');
        $cli->method('listRoutes')->willReturn('listroutes');
        $cli->method('noCommand')->willReturn('nocommand');

        $cli->method('wants')->will($this->returnArgument(0));

        $this->assertEquals($cli->wants('cache'), $cli->cache());
        $this->assertEquals($cli->wants('clearcache'), $cli->clearCache());
        $this->assertEquals($cli->wants('listroutes'), $cli->listRoutes());
        $this->assertEquals($cli->wants('nocommand'), $cli->noCommand());
    }

    /**
     * test no command
     * @return void
     */
    public function testNoCommandMethod()
    {
        $cli = $this->createMock(\Aven\Cli::class);

        $cli->method('writeLn')->willReturn('Command not found !');
        $cli->method('noCommand')->willReturn($cli->writeLn(''));

        $this->assertEquals($cli->noCommand(), 'Command not found !');
    }

}