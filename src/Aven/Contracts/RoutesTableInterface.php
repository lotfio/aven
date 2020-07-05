<?php

namespace Aven\Contracts;

interface RoutesTableInterface
{
    /**
     * add a route to the routing table
     *
     * @param string $method
     * @param string $uri
     * @param mixed  $action
     * @param string|null $group
     * @param string|null $groupName
     * @return void
     */
    public function addRoute(string $method, string $uri, $action, ?string $group = NULL, ?string $groupName, ?string $namespace);

    /**
     * initialize routing table by creating an array
     * that includes all the defined routes
     *
     * @return void
     */
    public function init() : void;

    /**
     * returns an array of defined routes
     * and keeps reference to it for later change
     * better than using several copies
     *
     * @return array
     */
    public function &getRoutes() : array;
}