<?php namespace Aven;

/**
 * Aven       Robust PHP Router 
 *
 * @package   Aven
 * @author    Lotfio Lakehal <lotfiolakehal@gmail.com>
 * @copyright 2016 Lotfio Lakehal
 * @license   MIT
 * @link      https://github.com/lotfio/aven
 */

use Aven\Exception\NotFoundException;
use Aven\Exception\RegExMisMatchException;
use Aven\Contracts\FilterInterface;

class Filter implements FilterInterface
{
    /**
     * route filters
     * 
     * @var array
     */
    public $filters = [];

    /**
     * filters order array
     * 
     * @var array
     */
    public $filtersOrder = [];


    /**
     * filter method
     * 
     * @param  array $filters parameters filters
     * @return void
     */
    public function setFilters($filters)
    {
        $this->filters[] = $filters;

        $last = count($this->filters) - 2;

        $this->filtersOrder[] = $last; // filters to be removed
    }

    /**
     * order filters method
     * unset empty filters that was set by __call method
     *
     * @return void
     */
    public function orderFilters()
    {

        if(count($this->filtersOrder) > 0) // if more than one route 
        {
            foreach ($this->filtersOrder as $key => $value) {
            
                unset($this->filters[$value]); // unset empty filter that was set by __call
            }
        }
    }

    /**
     * get filters method
     * 
     * @return array filters
     */
    public function getFilters()
    {
        $this->orderFilters();

        return array_values($this->filters);
    }

    /**
     * match filters with routes
     * 
     * @param  array $routes routes array
     * @return void
     */
    public function matchFilters($routes)
    {
        $i = 0;

        foreach ($routes as $route) {

            $route->filters = $this->getFilters()[$i];

            $i++;
        }
    }

    /**
     * filter parameters with regular expressions
     *
     * @param  $route
     * @throws \Exception
     */
    public function filterRegEx($route)
    {
        $filters = $route->filters; // route filters 
        $params  = $route->params; // route parameters 

        if(!empty($filters)) // validate routes 
        {
            foreach ($params as $param) { // null means optional 
                
                if(!is_null($param)) // not default 
                {
                    foreach ($filters as $key => $regex) {

                        if(!isset($params[$key])) { 
                            throw new NotFoundException("Error value of $key not found in parameters array", 500);
                        }

                        if(!preg_match($regex, $params[$key])) { 
                            throw new RegExMisMatchException("Error regular expression $regex != $params[$key]", 500);
                        }            
                    }
                }
            }
        } 
    }

}