<?php

declare(strict_types=1);

namespace Aven;

class RoutesParser
{
    /**
     * custom int regex matcher
     */
    private const PREDEFINED_FILTERS  = [

        '~\((:id(\?*\**)|:int(\?*\**)|:integer(\?*\**)|:num(\?*\**)||:numeric(\?*\**)|:number(\?*\**))\)~' => '\d',
        '~\{(:id(\?*\**)|:int(\?*\**)|:integer(\?*\**)|:num(\?*\**)||:numeric(\?*\**)|:number(\?*\**))\}~' => '\d',
        '~\((:str(\?*\**)|:string(\?*\**))\)~' => '\w',
        '~\((:alpha(\?*\**))\)~' => '[A-z]'
    ];

    /**
     * apply filters method
     *
     * apply user defined regex if any
     * apply custom regex
     *
     * @param array $routes
     * @return void
     */
    public function parse(array &$routes) : void
    {
        $uri  = '';

        foreach($routes as &$route)
        {
            $uri  = $this->replaceOptional($route['group'] . $route['uri']);

            // append namespace
            if(!$route['action'] instanceof \Closure)
                $route['action'] = $route['namespace'] . $route['action'];

            // user defined regex (from regex method)
            if(is_array($route['regex']) && count($route['regex']) > 0)
            {
                foreach($route['regex'] as $key => $val)
                {
                    $pattern = '~(\('. trim($key, '/') .'\))|(\{'. trim($key, '/') .'\})~';
                    $uri     = preg_replace($pattern, '(' . $val . ')', $uri);
                }
            }

            // apply predefined regex
            $uri = $this->replacePredefined(self::PREDEFINED_FILTERS, $uri);

            $uri = "~^" . $uri . "$~";

            $route['uri'] =  $uri; // ready
        }
    }

    /**
     * regex filter method
     *
     * should be named after what it is doing it is replacing and adding + by default
     *
     * @param  string $pattern
     * @param  string $subject
     * @return string
     */
    private function replacePredefined(array $patterns, string $subject) : string
    {
        foreach($patterns as $pattern => $rep)
        {
            $subject = preg_replace_callback($pattern, function($m) use ($rep){

                return (isset($m[count($m) - 1]) && ($m[count($m) - 1] == "?" || $m[count($m) - 1] == "*"))

                ? "(" . $rep . "{$m[count($m) - 1]})"

                : "(" . $rep . "+)";

            }, $subject);
        }

        return $subject;
    }

    /**
     * fix optional forward slash
     *
     * @param  string $uri
     * @return void
     */
    private function replaceOptional(string $uri)
    {
        $uri = '/' . trim($uri, '/');

        return preg_replace_callback('#((\/\([^\/]+\*\))|(\/\{[^\/]+\*\}))#', function($match) use ($uri){

          return '/?'. ltrim($match[0], '/');

        } ,$uri);
    }
}