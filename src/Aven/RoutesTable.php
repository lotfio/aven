<?php 

namespace Aven;

use Aven\Exceptions\RoutesTableException;
use Aven\Contracts\RoutesTableInterface;

class RoutesTable implements RoutesTableInterface
{
    /**
     * routing table
     *
     * @var array
     */
    private $routes = array();

    /**
     * route method
     *
     * @var array
     */
    private $route = array(
        'method'   => array(),
        'uri'      => array(),
        'regex'    => array(),
        'action'   => array(),
        'group'    => array(),
        'groupName'=> array(),
        'name'     => array()
    );

    /**
     * adding route method
     *
     * @param string $method
     * @param string $uri
     * @param mixed  $action
     * @param string|null $group
     * @param string|null $groupName
     * @return self
     */
    public function addRoute(string $method, string $uri, $action, ?string $group = NULL, ?string $groupName) : self
    {
        $this->route['method'][]     = $method;
        $this->route['uri'][]        = $uri;
        $this->route['regex'][]      = NULL;
        $this->route['action'][]     = $action;
        $this->route['group'][]      = $group;
        $this->route['groupName'][]  = $groupName;
        $this->route['name'][]       = NULL;

        return $this;
    }

    /**
     * add regex filter to a route
     *
     * @param array $regexe
     * @return void
     */
    public function regex(array $regexe) : self
    {
        $this->route['regex'][count($this->route['regex']) - 1] = $regexe; // always get the last since it has been initialized by setup
        return $this;
    }

    /**
     * route name method
     *
     * @param string $name
     * @return self
     */
    public function name(string $name) : self
    {
        if(in_array($name, $this->route['name']))
            throw new RoutesTableException("error route name ($name) assigned to another route already.");

        $this->route['name'][count($this->route['name']) - 1] = $name;

        return $this;
    }

    /**
     * setting up routing table
     *
     * @return void
     */
    public function init() : void
    {
        for($i = 0; $i < sizeof($this->route['method']); $i++)
        {
            $this->routes[] = array(
                "REQUEST_URI"   => "",
                "REGEX_URI"     => '/' . trim($this->route['uri'][$i], '/'),
                "REQUEST_METHOD"=> strtoupper($this->route['method'][$i]),
                "PARAMS_REGEX"  => isset($this->route['regex'][$i]) ? $this->route['regex'][$i] : [],
                "ACTION"        => $this->route['action'][$i],
                "NAME"          => trim($this->route['groupName'][$i] . $this->route['name'][$i], '.'),
                "GROUP"         => '/' . trim($this->route['group'][$i], '/')
            );
        }
    }

    /**
     * get routes method
     *
     * @return array
     */
    public function &getRoutes() : array 
    {
        return $this->routes;
    }
}