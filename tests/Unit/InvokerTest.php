<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Aven\Invoker;

class InvokerTest extends TestCase
{
    /**
     * test invoke wrong type
     *
     * @return void
     */
    public function testInvokeWrongType()
    {
        $this->expectException(\Aven\Exceptions\InvokerException::class);
        $invoker = new Invoker;
        $invoker('azaz', []);
    }

    /**
     * test invoke callback
     *
     * @return void
     */
    public function testInvokeCallback()
    {
        $invoker = new Invoker;
        $invoker(function(){ echo 50;}, []);
        $this->expectOutputString(50);
        ob_clean();

        $invoker(function($param){ return $param;}, ['param1']);
        $this->expectOutputString('param1');
    }

    /**
     * test invoke class method
     *
     * @return void
     */
    public function testInvokeWrongClassMethod()
    {
        $invoker = new Invoker;
        $this->expectException(\Aven\Exceptions\InvokerException::class);
        $invoker("TestClass@test", ['444']);
    }

    /**
     * test invoke class method
     *
     * @return void
     */
    public function testInvokeClassMethod()
    {
        $invoker = new Invoker;
        $invoker("\Tests\Unit\Stbs\TestController@test", ['1']);
        $this->expectOutputString("test pass 1");
    }

    /**
     * test invoke static class method
     *
     * @return void
     */
    public function testInvokeStaticClassMethod()
    {
        $invoker = new Invoker;
        $invoker("\Tests\Unit\Stbs\TestController::testStatic", ['2']);
        $this->expectOutputString("test pass 2");
    }
}