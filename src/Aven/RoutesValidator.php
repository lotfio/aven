<?php

declare(strict_types=1);

namespace Aven;

use Aven\Exceptions\RoutesValidatorException;

class RoutesValidator
{
    /**
     * check valid route
     * 
     * @param  array  $routes
     * @param  string $uri
     * @return array
     */
    public function validRoute(array &$routes, string $uri): array
    {
        for($i = 0; $i < count($routes); $i++)
        {
            if(preg_match($routes[$i]['uri'], $uri, $m)) // if uri matches a route
            {
                // wrong http method
                if(!$this->isValidHttpMethod($routes[$i]['method']))
                    continue; // check for other routes with same uri different method or exit not found

                // valid route
                $routes[$i]['uri']    = $uri;
                $routes[$i]['params'] = array_slice($m, 1);
 
                return $routes[$i];
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
     * @return bool
     */
    private function isValidHttpMethod(string $method): bool
    {

        if($method === $_SERVER['REQUEST_METHOD'] || $method === 'ANY')
            return TRUE;

        if(isset($_POST['_method']) && strtoupper($_POST['_method']) === $method)
            return TRUE;

        return FALSE;
    }
}