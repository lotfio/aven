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
use Aven\Contracts\DispatcherInterface;

class Dispatcher implements DispatcherInterface
{
    /**
     * routes dispatcher method
     *
     * @param  string $method
     * @param  array  $params
     * @throws \InvalidArgumentException
     * @return object
     */
    public function dispatch($method, $params = [])
    {
        if(empty($params)) { throw new \InvalidArgumentException("No Route Parameters Where Defined !", 500); }

        $pattern = preg_replace("/\{(.*?)\}/", "(?P<$1>.*)", $params[0]); // create pattern for parameters
        $pattern = "#^" . trim($pattern, '/') . "$#"; // final pattern
    
        $route['pattern'] = $pattern;
        // create route array
        $route['method']  = strtoupper($method);

        if(empty($params[1])) { throw new \InvalidArgumentException("Route Action is nedded", 500); }

        $route['action'] = $params[1];
        
        return (object)  $route;
    }

}