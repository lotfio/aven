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
use Aven\Contracts\FilterInterface;
use Aven\Contracts\MatcherInterface;

class Matcher implements MatcherInterface
{
    /**
     * request.
     *
     * @var object
     */
    public $request;

    /**
     * filter.
     *
     * @var object
     */
    public $filter;

    /**
     * Matcher constructor.
     *
     * @param Request         $request
     * @param FilterInterface $filter
     */
    public function __construct(Request $request, FilterInterface $filter)
    {
        $this->request = $request;
        $this->filter = $filter;
    }

    /**
     * route match method.
     *
     * @param array $routes
     *
     * @throws Exception\NotFoundException
     * @throws \Exception
     *
     * @return mixed
     */
    public function match($routes) // match defined routes with requested uris and set up routing table
    {
        foreach ($routes as $route) {
            if (preg_match($route->pattern, $this->request->uri(), $matches) && $this->request->isValidHttpMethod($route->method)) {
                foreach ($matches as $key => $value) {
                    if ($key === 0) {
                        $route->uri = $value;
                    }
                    if (is_numeric($key)) {
                        unset($matches[$key]);
                    }
                }

                $route->params = $matches;

                $this->filter->filterRegEx($route);

                return $route;
            }
        }

        $this->request->notFoundRoute();
    }
}
