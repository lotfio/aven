<?php

declare(strict_types=1);

namespace Aven;

use Aven\Exceptions\RouterException;
use Aven\Contracts\RoutesTableInterface;

class Router
{
    /**
     * router trait
     */
    use RouterTrait;

    /**
     * available route methods
     *
     * @var array
     */
    private $availMethods = [
        'GET', 'POST', 'PUT', 'DELETE', 'ANY', 'HEAD'
    ];

    /**
     * routes table
     *
     * @var object
     */
    private $routesTable;

    /**
     * routes parser object
     *
     * @var object
     */
    private $parser;

    /**
     * routes validator
     *
     * @var object
     */
    private $validator;

    /**
     * avail routes
     *
     * @var array
     */
    private $routes;

    /**
     * request uri
     *
     * @var string
     */
    private $uri;

    /**
     * set up
     *
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $this->uri = $uri;

        $this->routesTable  = new RoutesTable;
        $this->parser       = new RoutesParser;
        $this->validator    = new RoutesValidator;
    }

    /**
     * dynamic method calls
     *
     * @param string $method
     * @param array  $params
     * @return void
     */
    public function __call($method, $params) : RoutesTableInterface
    {
        if(!in_array(strtoupper($method), $this->availMethods))
            throw new RouterException("request method ($method) not allowed.");

        if(!isset($params[0]) || !isset($params[1]))
            throw new RouterException("route uri and action are required.");

        return $this->routesTable->add($method, $params[0], $params[1]);
    }

    /**
     * initialize router
     *
     * @return void
     */
    public function init()
    {
        // set routes
        $this->routes = $this->routesTable->getRoutes();

        // parse routes and apply regex patterns
        $this->parser->parse($this->routes);

        // valid route
        $validRoute = $this->validator->validRoute($this->routes, $this->uri);

        // invoke
        return (new Invoker)($validRoute['action'], $validRoute['params']);
    }
}
