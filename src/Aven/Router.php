<?php 

namespace Aven;

use Aven\Exceptions\RouterException;

class Router
{
    use RouterTrait;

    /**
     * available route methods
     *
     * @var array
     */
    private $availMethods = array(
        'GET', 'POST', 'PUT', 'DELETE', 'ANY'
    );

    /**
     * routing table object
     *
     * @var object
     */
    private $table;

    /**
     * available routes
     *
     * @var array
     */
    private $routes;

    /**
     * routes filter object
     *
     * @var object
     */
    private $filter; 

    /**
     * route group and name
     *
     * @var ?string
     */
    public  $group     = NULL;
    public $groupName = NULL;


    private $uri;

    /**
     *
     * 
     */
    public function __construct(string $uri)
    {
        $this->table  = new RoutesTable;
        $this->filter = new RoutesFilter;

        // should be treated somewhere
        $this->uri = $uri;
    }


    public function __call($method, $params)
    {
        if(!isset($params[0]) || !isset($params[1]))
            throw new RouterException("route uri and action are required.");

        if(!is_string($params[0]) || strpos($params[0], '~') !== FALSE)
            throw new RouterException("route uri must be a valid string and (~) character is not allowed.");
        
        if(!in_array(strtoupper($method), $this->availMethods))
            throw new RouterException("request method ($method) not allowed.");
        
        if(!$params[1] instanceof \Closure && !is_string($params[1]))
            throw new RouterException("route action must be avalid string or a callback");
        
        return $this->table->addRoute($method, $params[0], $params[1], $this->group, $this->groupName);
    }
    

    public function init()
    {
        // should be initiated after the calls 
        $this->table->init();

        $this->routes = $this->table->getRoutes();

        // should be filtered after initiated in table
        $this->filter->applyFilters($this->routes);


        (new RoutesValidator)->isValidRoute($this->routes, $this->uri);
    }
}
