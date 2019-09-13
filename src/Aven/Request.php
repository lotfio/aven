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

use Aven\Exception\NotFoundException;

class Request
{
    /**
     * request all method
     * return all fields.
     *
     * @return object
     */
    public function all()
    {
        return (object) $_REQUEST;
    }

    /**
     * request method.
     *
     * @return mixed
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * request uri.
     *
     * @return request uri table
     */
    public function uri()
    {
        // remove sub directories if any
        $uri = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));

        unset($uri[count($uri) - 1]);

        $uri = str_replace(implode('/', $uri), '', $_SERVER['REQUEST_URI']);

        return trim($uri, '/');
    }

    /**
     * check if valid request method.
     *
     * @param  $method
     *
     * @return bool
     */
    public function isValidHttpMethod($method)
    {
        if ($this->method() === $method || $method === 'ANY') {
            return true;
        } // methods are okay

        if (isset($this->all()->_method) && strtoupper($this->all()->_method) === $method) {
            return true;
        }

        return false;
    }

    /**
     * not found route.
     *
     * @throws NotFoundException
     */
    public function notFoundRoute()
    {
        // Error Route
        throw new NotFoundException(' EROR 404 No Page was found !', 404);
    }
}
