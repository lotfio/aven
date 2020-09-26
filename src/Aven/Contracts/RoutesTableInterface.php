<?php

namespace Aven\Contracts;

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