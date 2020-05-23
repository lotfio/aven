<?php

declare(strict_types=1);

namespace Aven;

use Aven\Exceptions\RouterException;

trait RouterTrait
{
    /**
     * group route method
     *
     * @param string $uri
     * @param callable $callback
     * @param string $name group name
     * @return void
     */
    public function group(string $uri, callable $callback, string $name = NULL) : void
    {
        $this->group        .= $uri;
        $this->groupName    .= $name . ".";
        $callback($this);
        $this->group        = trim($this->group, $uri); // if recursive remove previous group
        $this->groupName    = trim($this->groupName, $name . ".");
    }

    /**
     * form route method
     *
     * @param string $uri
     * @param callable $callback
     * @return void
     */
    public function form(string $uri, $callback, array $customMethods = [], string $name = NULL) : void
    {
        $this->group($uri, function($router) use ($callback, $customMethods){

            $get  = $callback;
            $post = $callback;

            if(is_string($callback))
            {
                $get   = isset($customMethods[0]) ? "$callback@{$customMethods[0]}" : "$callback@showForm";
                $post  = isset($customMethods[1]) ? "$callback@{$customMethods[1]}" : "$callback@submitForm";
            }

            $router->get('',  $get);
            $router->post('', $post);

        }, $name);
    }

    /**
     * crud route method
     *
     * @param string $uri
     * @param callable $callback
     * @return void
     */
    public function crud(string $uri, $controller, string $name = NULL) : self
    {
        $this->group($uri, function($router) use ($controller){
            $router->post('/create',            "$controller@create")->name("create");
            $router->get('/read/(:id*)',        "$controller@read")->name("read");
            $router->put('/update/(:id)',       "$controller@update")->name("update");
            $router->delete('/delete/(:id)',    "$controller@delete")->name("delete");
        }, $name);

        return $this;
    }

    /**
     * redirect routes by name
     *
     * @param string $name
     * @param array $params
     * @param integer $httpCode
     * @return void
     */
    public function redirect(string $name, array $params = [], int $httpCode = 301) : void
    {
        foreach($this->routes as $route)
        {
            if($name === $route['NAME'])
            {
                $to = $route['REGEX_URI'];

                if(preg_match_all('~\(.*?\)|\{.*?\}~', $to, $m))
                {
                    // check if number of parameters matches uri parameters
                    if(isset($m[0]) && count($m[0]) > 0 && count($m[0]) !== count($params))
                        throw new RouterException("Error route ($name) => ($to) parameters do not match ". implode(',', $m[0]) ." => (". implode(',', $params) .")", 50);

                    // replace provided params in uri params
                    for($i = 0; $i < count($m[0]); $i++)
                        $to = str_replace($m[0][$i], $params[$i], $to);
                }

                $to = str_replace('\/', '/', trim($to, '~^$'));
                header('location:' . $to, TRUE, $httpCode);
                exit;
            }
        }

        throw new RouterException("Error redirect route ($name) not found.", 50);
    }

    //  implement crud only + except
}