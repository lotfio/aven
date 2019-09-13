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
use Aven\Contracts\ResolverInterface;
use Aven\Exception\NotFoundException;

class Resolver implements ResolverInterface
{
    /**
     * route initiate method.
     *
     * @param object $table
     * @param null   $namespace
     *
     * @throws NotFoundException
     */
    public function initiateRoute($table, $namespace = null)
    {

        /*
         * if is callback
         */
        if ($table->action instanceof \Closure) {
            $return = call_user_func_array($table->action, $table->params);

            return $this->resolveByType($return);
        }

        /*
         * controller
         */
        if (preg_match("/\w+@\w+/", $table->action)) {
            $call = explode('@', $table->action);
            $controller = $namespace.$call[0];
            $action = $call[1];
            $params = $table->params;

            $this->checkControllerAndMethod($controller, $action);
            $controller = new $controller();

            return $this->resolveController($controller, $action, $params);
        }

        /*
         * static call
         */

        if (preg_match("/\w+::\w+/", $table->action)) {
            $call = explode('::', $table->action);
            $controller = $namespace.$call[0];
            $action = $call[1];
            $params = $table->params;

            $this->checkControllerAndMethod($controller, $action);

            return $this->resolveController($controller, $action, $params);
        }

        throw new NotFoundException(' EROR 404 No Page was found !', 404);
    }

    /**
     * resolve by returned type.
     *
     * @param mixed $data
     *
     * @return void
     */
    public function resolveByType($data)
    {
        switch (gettype($data)) {

        case 'array':
        case 'object':
            header('Content-Type: application/json');
            echo json_encode($data);
            break;
        default: echo $data;
            break;
        }
    }

    /**
     * resolve controller method.
     *
     * @param mixed  $controller
     * @param string $action
     * @param array  $params
     *
     * @return void
     */
    public function resolveController($controller, $action, $params)
    {
        $return = call_user_func_array([$controller, $action], $params);

        return $this->resolveByType($return);
    }

    /**
     * check controller and action.
     *
     * @param $controller
     * @param $action
     *
     * @throws NotFoundException
     */
    public function checkControllerAndMethod($controller, $action)
    {
        if (!class_exists($controller)) {
            throw new NotFoundException("Controller $controller not found ! ", 404);
        }
        if (!method_exists($controller, $action)) {
            throw new NotFoundException("Method $action not found ! ", 404);
        }
    }
}
