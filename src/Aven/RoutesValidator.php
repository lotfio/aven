<?php

declare(strict_types=1);

namespace Aven;

use Aven\Exceptions\RoutesValidatorException;

class RoutesValidator
{
    /**
     * check valid route and invoke it
     *
     * @param array  $routes
     * @param string $uri
     * @return boolean
     */
    public function isValidRoute(array &$routes, string $uri)
    {
        for($i = 0; $i < count($routes); $i++)
        {
            if(preg_match($routes[$i]['uri'], $uri, $m)) // if uri matches a route
            {
                // wrong http method
                if(!$this->isValidHttpMethod($routes[$i]['method']))
                    continue; // check for other routes with same uri different method or exit not found

                // valid route
                $routes[$i]['uri'] = $uri;

                return (new Invoker)($routes[$i]['action'], array_slice($m, 1));
            }
        }
        // not found route
        throw new RoutesValidatorException("error route ($uri) not found.", 40);
    }

    /**
     * check if valid http method
     *
     * check GET or POST from server
     * for   ANY allow all
     * for the rest method should be sent with http data _method
     *
     * @param  string $method
     * @return boolean
     */
    private function isValidHttpMethod(string $method)
    {

        if($method === $_SERVER['REQUEST_METHOD'] || $method === 'ANY')
            return TRUE;

        if(isset($_POST['_method']) && strtoupper($_POST['_method']) === $method)
            return TRUE;

        return FALSE;
    }
}