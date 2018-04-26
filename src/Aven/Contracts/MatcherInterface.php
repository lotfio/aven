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

interface MatcherInterface
{
    /**
     * match routes method
     *
     * loop through available routes and match request uri with 
     * the exact route and the valid HTTP method
     * 
     * @param  array $routes available routes
     * @return mixed matched route as object or exception not found route
     */
    public function match($routes);
}