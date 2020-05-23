<?php

namespace Tests\Unit\Stbs;

class TestController
{
    public function test($param)
    {
        return "test pass $param";
    }

    public static function testStatic($param)
    {
        return "test pass $param";
    }

    public function formatedOutput()
    {
        return array();
    }

    public function testValid()
    {
        return 'valid route';
    }
}