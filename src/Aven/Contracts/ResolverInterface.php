<?php namespace Aven\Contracts;

/**
 * Aven       Robust PHP Router 
 *
 * @package   Aven
 * @author    Lotfio Lakehal <lotfiolakehal@gmail.com>
 * @copyright 2016 Lotfio Lakehal
 * @license   MIT
 * @link      https://github.com/lotfio/aven
 */

interface ResolverInterface
{
    /**
     * route initiate method 
     *
     * this method will initiate the matched route based on the 
     * action type if it is a closure a controller method or 
     * a controller static method
     * 
     * @param  object $table     matched route
     * @param  string $namespace controller namespace
     * @return void callback call
     */
    public function initiateRoute($table, $namespace = null);
}