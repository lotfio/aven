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
     * @return void
     */
    public function add(string $method, string $uri, $action);

    /**
     * returns an array of defined routes
     *
     * @return array
     */
    public function &getRoutes() : array;
}