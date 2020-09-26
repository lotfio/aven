<?php declare(strict_types=1);

namespace Aven;

/*
 * This file is a part of aven
 *
 * @package     Aven
 * @version     1.0.0
 * @author      Lotfio Lakehal <contact@lotfio.net>
 * @copyright   Lotfio Lakehal 2019
 * @license     MIT
 * @link        https://github.com/lotfio/aven
 *
 */

use Aven\Exceptions\InvokerException;

class Invoker
{
    /**
     * invoke a route
     *
     * @param array $route
     * @return void
     */
    public function __invoke($action, array $params)
    {
        if($action instanceof \Closure)
            return $this->callback($action, $params);

        if(preg_match('~\w+(@|::)\w+~', $action, $m))
        {
            $action = explode($m[1], $action);
            return $this->classMethod($action[0], $action[1], $params);
        }

        throw new InvokerException("error action ($action), only callbacks and class methods are allowed.", 50);
    }

    /**
     * invoke callback
     *
     * @param callable $action
     * @param array $params
     * @return void
     */
    private function callback(callable $action, array $params)
    {
        return formatOutput(call_user_func($action, ...$params));
    }

    /**
     * invoke class method
     *
     * @param  string $class
     * @param  string $method
     * @param  array  $params
     * @return void
     */
    private function classMethod(string $class, string $method, array $params)
    {
        if(!class_exists($class))
            throw new InvokerException("class ($class) not found.", 40);

        $ins = new $class();

        if(!method_exists($ins, $method))
            throw new InvokerException("method ($method) does not exists in controller $class.", 40);

        return formatOutput(call_user_func_array([$ins, $method], $params));
    }
}