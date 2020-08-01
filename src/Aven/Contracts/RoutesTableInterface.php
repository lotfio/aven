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
    public function add(string $method, string $uri, $action);

    /**
     * returns an array of defined routes
     * and keeps reference to it for later change
     * better than using several copies
     *
     * @return array
     */
    public function &getRoutes() : array;
}