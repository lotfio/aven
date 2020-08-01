<?php

declare(strict_types=1);

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
    private $routes = [];

    /**
     * route group
     *
     * @var ?string
     */
    private $routeGroup = null;

    /**
     * route group
     *
     * @var ?string
     */
    private $routeNamespace = null;

    /**
     * adding route method
     *
     * @param string $method
     * @param string $uri
     * @param mixed  $action
     * @return self
     */
    public function add(string $method, string $uri, $action) : self
    {
        $this->routes[] = [
            'method'    => strtoupper($method),
            'uri'       => '/' . trim($uri, '/'),
            'action'    => $action,
            'regex'     => [],
            'name'      => null,
            'group'     => '/' . trim(preg_replace('~\/{2,}~', '/', $this->routeGroup), '/'),
            'namespace' => $this->routeNamespace
        ];

        return $this;
    }

    /**
     * current route element
     *
     * @param string $elem
     * @param mixed $value
     * @return void
     */
    private function current(string $elem, $value) : void
    {
        $this->routes[count($this->routes) - 1][$elem] = $value;
    }

    /**
     * add regex filter to a route
     *
     * @param array $regex
     * @return void
     */
    public function regex(array $regex) : self
    {
        if(!is_array($regex))
            throw new RoutesTableException("route regex must be an array.");

        $this->current('regex', $regex);
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
        $this->current('name', $name);
        return $this;
    }

    /**
     * set group
     *
     * @param string $group
     * @return void
     */
    public function setGroup(string $group) : void
    {
        $this->routeGroup .= '/' . trim($group, '/') . '/';
    }

    /**
     * unset group
     *
     * @param string $group
     * @return void
     */
    public function unsetGroup(string $group) : void
    {
        $this->routeGroup = str_replace('/' . trim($group, '/') . '/', NULL, $this->routeGroup);
    }

    /**
     * set namespace
     *
     * @param string $namespace
     * @return void
     */
    public function setNamespace(string $namespace) : void
    {
        $this->routeNamespace   .= '\\' . trim(str_replace('.', '\\', $namespace), '\\') . '\\';
        $this->routeNamespace    = str_replace('\\\\', '\\', $this->routeNamespace);
    }

    /**
     * unset namespace
     *
     * @param string $namespace
     * @return void
     */
    public function unsetNamespace(string $namespace) : void
    {
        $this->routeNamespace = trim($this->routeNamespace, '\\' . trim(str_replace('.', '\\', $namespace), '\\') . '\\');
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