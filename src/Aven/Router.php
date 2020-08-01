<?php

declare(strict_types=1);

namespace Aven;

use Aven\Exceptions\RouterException;
use Aven\Contracts\RoutesTableInterface;

class Router
{
    use RouterTrait;

    /**
     * available route methods
     *
     * @var array
     */
    private $availMethods = array(
        'GET', 'POST', 'PUT', 'DELETE', 'ANY', 'HEAD'
    );

    /**
     * routes filter object
     *
     * @var object
     */
    private $filter;

    /**
     * routes validator
     *
     * @var object
     */
    private $validator;

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
        $this->filter       = new RoutesFilter;
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
        $routes = $this->routesTable->getRoutes();

        // should be filtered after initiated in table
        $this->filter->applyFilters($routes);


        // validate and invoke valid route
        $this->validator->isValidRoute($routes, $this->uri);
    }
}
