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

interface FilterInterface
{

    /**
     * set filters method
     * this method will set filters on filters array
     * each filter call on router will push to the filters array
     * otherwise the __call magic method will push an empty value
     * 
     * @param  array filters
     * @return void
     */
    public function setFilters($filters);

    /**
     * order filters method
     * unset empty filters that was set by __call method
     *
     * @return void
     */
    public function orderFilters();

    /**
     * getFilters method
     * 
     * @return array of available filters
     */
    public function getFilters();

    /**
     * match filters method 
     * match filters with routes
     * 
     * @param  array $routes routes array
     * @return void
     */
    public function matchFilters($routes);
}