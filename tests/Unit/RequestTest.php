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

class RequestTest extends TestCase
{
    /**
     * request class
     * 
     * @var object
     */
    private $request;


    public function setUp() : void
    {
        $this->request = new \Aven\Request;
    }

    /**
     * test request all is returning an object of all request inputs
     * 
     * @return void
     */
    public function testRequestAllReturnsObject()
    {
        $this->assertIsObject($this->request->all());
    }

    /**
     * test request method is returning string method
     * 
     * @return void
     */
    public function testRequestMethodIsReturningAstringMethod()
    {   
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->assertIsString($this->request->method());
    }

    /**
     * test isValidMethods
     * 
     * @return void
     */
    public function testIsValidHttpMethodReturningTrueOnRightMethod()
    {
        /**
         * available router HTTP methods
         * @var array
         */
        $methods = ['GET', 'POST', 'PUT', 'PATCH','DELETE',
        'COPY', 'HEAD', 'OPTIONS', 'LINK', 'UNLINK', 'PURGE',
        'LOCK', 'UNLOCK', 'PROPFIND','ANY'];

        /**
         * assert true if HTTP method is set
         */
        foreach ($methods as $method) {
            
            $_SERVER['REQUEST_METHOD'] = $method;
            $this->assertTrue($this->request->isValidHttpMethod($method));

        }

    }

    /**
     * assert notFoundException is thrown
     * 
     * @return void
     */
    public function testNotFoundRouteIsThrowingNotFoundException()
    {
        $this->expectException(\Aven\Exception\NotFoundException::class);
        $this->request->notFoundRoute();
    }

}