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

use Aven\Exceptions\RoutesValidatorException;
use PHPUnit\Framework\TestCase;
use Aven\RoutesValidator;

class RoutesValidatorTest extends TestCase
{
    /**
     * example route
     *
     * @var array
     */
    protected $routes = [
        [
            'method'    => '',
            'uri'       => '',
            'action'    => '',
            'regex'     => '',
            'name'      => '',
            'group'     => '',
            'namespace' => ''
        ]
    ];
    
    /**
     * route validator
     *
     * @var object
     */
    protected $validator;

    /**
     * set up
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->validator           = new RoutesValidator;
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    /**
     * test validate route
     */
    public function testValidateRoute()
    {
        $this->expectException(RoutesValidatorException::class);
        $this->routes[0]['uri'] = '~^/test$~';
        $this->validator->validRoute($this->routes, '/test');
    }
}