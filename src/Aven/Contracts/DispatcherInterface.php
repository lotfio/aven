<?php

namespace Aven\Contracts;

/**
 * Aven       Robust PHP Router.
 *
 * @author    Lotfio Lakehal <lotfiolakehal@gmail.com>
 * @copyright 2016 Lotfio Lakehal
 * @license   MIT
 *
 * @link      https://github.com/lotfio/aven
 */
interface DispatcherInterface
{
    /**
     * dispatch routes method.
     *
     * this method takes two parameters, method call which is an http verb like
     * get post put patch delete purge and others like group, namespace, any
     * and a parameters array which is the route name like '/index/page1' and the callback action
     *
     * @param string $method method or http verb
     * @param array  $params route parameters
     *
     * @return array dispatched route
     */
    public function dispatch($method, $params);
}
