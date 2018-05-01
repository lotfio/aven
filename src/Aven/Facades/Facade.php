<?php

namespace Aven\Facades;

/*
 * Aven       Robust PHP Router
 *
 * @package   Aven
 * @author    Lotfio Lakehal <lotfiolakehal@gmail.com>
 * @copyright 2016 Lotfio Lakehal
 * @license   MIT
 * @link      https://github.com/lotfio/aven
 */

use Aven\Dispatcher;
use Aven\Filter;
use Aven\Matcher;
use Aven\Request;
use Aven\Resolver;
use Aven\Router;

class Facade
{
    /**
     * router.
     *
     * @var object
     */
    public static $router;

    /**
     * This is just a base facade class it will transform
     * static calls to object call.
     *
     * You can name you router what ever you want just extend this class
     *
     * @param  $method
     * @param  $params
     *
     * @return mixed
     */
    public static function __callStatic($method, $params)
    {
        if (!isset(self::$router)) {
            $request = new Request();
            $dispatcher = new Dispatcher();
            $filter = new Filter();
            $resolver = new Resolver();
            $matcher = new Matcher($request, $filter);
            self::$router = new Router($dispatcher, $filter, $matcher, $resolver);
        }

        return (self::$router)->{$method}(...$params);
    }
}
