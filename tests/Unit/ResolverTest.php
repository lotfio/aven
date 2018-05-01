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

class ResolverTest extends TestCase
{
    /**
     * route resolver.
     *
     * @var object
     */
    private $resolver;

    /**
     *  route table.
     *
     * @var object
     */
    private $table;

    /*
     * setting up resolver
     */
    public function setUp()
    {
        $this->resolver = new \Aven\Resolver();

        $this->table = (object) [
            'pattern'=> '#^$#',
            'method' => 'GET',
            'uri'    => '/',
            'params' => [],
            'action' => function () {
                return 'from callback';
            },
            'filters'=> '',
        ];
    }

    /**
     * test resolver is calling callback function.
     *
     * @return void
     */
    public function testInitiateClosure()
    {
        $namespace = '';

        $this->resolver->initiateRoute($this->table, $namespace);

        $this->expectOutputString('from callback');
    }

    /**
     * test resolver is calling controller method.
     *
     * @return void
     */
    public function testInitiateControllerAndMethod()
    {
        $this->table->action = 'TestController@index';

        $namespace = "Tests\Unit\Stubs\\";

        $this->resolver->initiateRoute($this->table, $namespace);

        $this->expectOutputString('from Test controller index method');
    }

    /**
     * test resolver is calling call static method.
     *
     * @return void
     */
    public function testInitiateControllerStaticAndMethod()
    {
        $this->table->action = 'TestController::staticmethodCall';

        $namespace = "Tests\Unit\Stubs\\";

        $this->resolver->initiateRoute($this->table, $namespace);

        $this->expectOutputString('from Test controller static method');
    }

    /**
     * test checkControllerAndMethod not found controller.
     *
     * @return void
     */
    public function testCheckControllerAndMethodNotFoundController()
    {
        $controller = '';
        $method = '';

        $this->expectException(\Aven\Exception\NotFoundException::class);
        $this->resolver->checkControllerAndMethod($controller, $method);
    }

    /**
     * test checkControllerAndMethod not found method.
     *
     * @return void
     */
    public function testCheckControllerAndMethodNotFoundMethod()
    {
        $controller = "\Tests\Unit\Stubs\TestController";
        $method = '';

        $this->expectException(\Aven\Exception\NotFoundException::class);
        $this->resolver->checkControllerAndMethod($controller, $method);
    }
}
