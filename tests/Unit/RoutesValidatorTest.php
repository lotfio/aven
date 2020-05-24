<?php

namespace Tests\Unit;

use Aven\Exceptions\RoutesValidatorException;
use PHPUnit\Framework\TestCase;
use Aven\RoutesValidator;

class RoutesValidatorTest extends TestCase
{
    private $routes;
    private $validator;

    /**
     * set up 
     *
     * @return void
     */
    public function setUp() : void
    {
        $this->routes = array(
            array(
                'REQUEST_URI'    => '',
                'REGEX_URI'      => '~^\/test$~',
                'REQUEST_METHOD' => 'GET',
                'PARAMS_REGEX'   => [],
                'ACTION'         => '\Tests\Unit\Stbs\TestController@testValid',
                'NAME'           => 'test',
                'GROUP'          => '',
            )
        );
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->validator = new RoutesValidator;
    }

    /**
     * test wrong route
     *
     * @return void
     */
    public function testIsValidRouteWrong()
    {
        $this->expectException(RoutesValidatorException::class);
        $this->validator->isValidRoute($this->routes, '/');
    }

    /**
     * test valid route
     *
     * @return void
     */
    public function testIsValidRouteValid()
    {
        $this->validator->isValidRoute($this->routes, '/test');
        $this->expectOutputString('valid route');
    }
}