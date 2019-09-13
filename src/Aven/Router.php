<?php

namespace Aven;

/*
 * Aven       Robust PHP Router
 *
 * @package   Aven
 * @author    Lotfio Lakehal <lotfiolakehal@gmail.com>
 * @copyright 2016 Lotfio Lakehal
 * @license   MIT
 * @link      https://github.com/lotfio/aven
 */

use Aven\Contracts\DispatcherInterface;
use Aven\Contracts\FilterInterface;
use Aven\Contracts\MatcherInterface;
use Aven\Contracts\ResolverInterface;
use Aven\Exception\NotFoundException;

class Router
{
    /**
     * HTTP verbs
     * Available router methods.
     *
     * @var array routes
     */
    private $methods = [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
        'COPY',
        'HEAD',
        'OPTIONS',
        'LINK',
        'UNLINK',
        'PURGE',
        'LOCK',
        'UNLOCK',
        'PROPFIND',
        'ANY',
    ];

    /**
     * defined Routes.
     *
     * @var array
     */
    private $routes = [];

    /**
     * dispatcher object.
     *
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * route filter.
     *
     * @var array
     */
    public $filter;

    /**
     * route filter.
     *
     * @var array
     */
    public $matcher;

    /**
     * Routing Table
     * Found route on call will be registered here
     * and called by the init method.
     *
     * @var array
     */
    private $table;

    /**
     * route resolver.
     *
     * @var object
     */
    public $resolver;

    /**
     * Router config.
     *
     * @var array
     */
    public $config = [

        'namespace' => '',
        'cache'     => __DIR__,
    ];

    /**
     * cache file.
     *
     * @var string
     */
    public $cacheFile = 'routes.cache';

    /**
     * constructor.
     *
     * @param DispatcherInterface $dispatcher
     * @param FilterInterface     $filter
     * @param MatcherInterface    $matcher
     * @param ResolverInterface   $resolver
     */
    public function __construct(DispatcherInterface $dispatcher, FilterInterface $filter,
        MatcherInterface $matcher, ResolverInterface $resolver
    ) {
        // init dispatcher
        $this->dispatcher = $dispatcher;

        $this->filter = $filter;

        $this->matcher = $matcher;

        $this->resolver = $resolver;
    }

    /**
     * Router dynamic call to available HTTP Methods.
     *
     * @param  $method
     * @param  $params
     *
     * @throws NotFoundException
     *
     * @return $this
     */
    public function __call($method, $params)
    {
        /**
         * check if the route method is available.
         */
        $method = strtoupper($method);
        if (!in_array($method, $this->methods)) {
            throw new NotFoundException("Method $method Not Found !", 500);
        }

        /*
         * dispatch the routes if not from cache
         */
        if (!$this->fromCache($this->cacheFile)) {
            $this->routes[] = $this->dispatcher->dispatch($method, $params);
            /*
             * each method call is going to increment to the filters array
             *  if there is a filter method called after that it s going to remove
             *  the previous empty value and replace it with the filter value
             */
            $this->filter->filters[] = false; // set empty filter if not filtered
        }

        return $this;
    }

    /**
     * Router initiate method.
     *
     * @throws \Exception
     */
    public function init() // initiate routing table cal route
    {
        if (php_sapi_name() !== 'cli') {
            if (!$this->fromCache($this->cacheFile)) {
                $this->filter->matchFilters($this->routes); // match filters with routes

                $this->sortRoutes(); // sort routes

                $this->table = $this->matcher->match($this->routes);
            } else {
                $this->table = $this->matcher->match($this->fromCache($this->cacheFile));
            }

            // match routes with uri
            $namespace = isset($this->config['namespace']) ? $this->config['namespace'] : '';

            return $this->resolver->initiateRoute($this->table, $namespace); // initiate route
        }
    }

    /**
     * sort routes method.
     *
     * @return void
     */
    public function sortRoutes()
    {
        usort(
            $this->routes, function ($a, $b) {
                // sort routes based on uri
                return strlen($b->pattern) - strlen($a->pattern);
            }
        );
    }

    /**
     * filter method.
     *
     * @param array $filters parameters filters
     *
     * @return void
     */
    public function filter($filters)
    {
        $this->filter->setFilters($filters);
    }

    /**
     * router config method.
     *
     * @param  $array config array
     *
     * @return void
     */
    public function config($array)
    {
        $this->config = $array;
    }

    /**
     * get config method.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getConfig($key)
    {
        return isset($this->config[$key]) ? $this->config[$key] : false;
    }

    /**
     * get Routes method.
     *
     * @return array
     */
    public function getRoutes()
    {
        $this->filter->matchFilters($this->routes); // match filters nedded for cli
        $this->sortRoutes($this->routes);

        return $this->routes; // return array of routes
    }

    /**
     * load routes from cache !
     *
     * @param string $filename filename
     *
     * @return array|bool
     */
    public function fromCache()
    {
        $file = rtrim($this->config['cache'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->cacheFile;

        if (file_exists($file)) {
            return require $file;
        }

        return false;
    }
}
