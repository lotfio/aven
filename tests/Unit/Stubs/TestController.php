<?php namespace Tests\Unit\Stubs;

class TestController
{
    public function index()
    {
        return "from Test controller index method";
    }

    public static function staticmethodCall()
    {   
        return "from Test controller static method";
    }
}