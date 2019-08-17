<?php

/**
 * Aven          Robust PHP Router 
 *
 * @package      Aven
 * @author       Lotfio Lakehal <lotfiolakehal@gmail.com>
 * @copyright    2016 Lotfio Lakehal
 * @license      MIT
 * @link         https://github.com/lotfio/aven
 *
 */

use PHPUnit\Framework\TestCase;
use Aven\Filter;

class FilterTest extends TestCase
{
    /**
     * test filter is adding filters to the filter array
     * 
     * @return void
     */
    public function testsetFiltersIsFillingFiltersArray()
    {
        $filter = new Filter;

        $filterExample = [
            "id" => "/[a-z]]+/"
        ];

        $filter->setFilters($filterExample); // added by filter
        $filter->setFilters(""); // added by __call
        $filter->setFilters($filterExample);
        $filter->setFilters("");
        
        $this->assertIsArray($filter->filters);
        $this->assertArrayHasKey('id', $filter->filters[0]);
    }

    /**
     *  test filter routesOrder is adding filter orders
     *  if called from filter() adds filters 
     *  if called from__call adds empty values
     * 
     * @return void
     */
    public function testsetFiltersHasOrders()
    {
        $filter = new Filter;

        $filterExample = [
            "id" => "/[a-z]]+/"
        ];

        $filter->setFilters($filterExample); // added by filter
        $filter->setFilters(""); // added by __call
        $filter->setFilters($filterExample);
        $filter->setFilters("");

        $this->assertIsArray($filter->filtersOrder);
    }


    /**
     * test filters order
     * @return void
     */
    public function testFiltersOrderIsOrderingFilters()
    {
        $filter = new Filter;

        $filterExample = [
            "id" => "/[a-z]]+/"
        ];

        $filter->setFilters($filterExample); // added by filter
        $filter->filters[] = false; // added by __call
        $filter->setFilters($filterExample);
        $filter->filters[] = false;
        $filter->setFilters($filterExample);

        $filter->orderFilters(); // order filters 

        // assert filters is ordered 
        //expected 0 2 4
        $this->assertEquals($filter->filters[0], $filterExample);
        $this->assertEquals($filter->filters[2], $filterExample);
        $this->assertEquals($filter->filters[4], $filterExample);
    }

    /**
     *  test get filters 
     * @return void
     */
    public function testGetFiltersIsReturningAnArrayOfFilters()
    {
        $filter = new Filter;

        $filterExample = [
            "id" => "/[a-z]]+/"
        ];

        $filter->setFilters($filterExample); // added by filter
        $filter->filters[] = false; // added by __call
        $filter->setFilters($filterExample);
        $filter->filters[] = false;
        $filter->setFilters($filterExample);

        $filter->orderFilters(); // order filters 

        $this->assertIsArray($filter->getFilters()); 
    }


    /**
     *  test assign filters to routes
     * @return void
     */
    public function testMatchFiltersWithRoutes()
    {
        $filter = new Filter;

        $filterExample = [
            "id" => "/[a-z]]+/"
        ];

        $filter->setFilters($filterExample); // added by filter
        $filter->filters[] = false; // added by __call
        $filter->setFilters($filterExample);
        $filter->filters[] = false;
        $filter->setFilters($filterExample);

        $filter->orderFilters(); // order filters

        $routes = [
                    (object) [
                        'method' => 'GET',
                        'uri' => '',
                        'params' => [],

                        'action' => 'IndexController@index',
                        'filters' => null
                    ],
                    (object) [
                        'method' => 'GET',
                        'uri' => '',
                        'params' => [],

                        'action' => 'IndexController@index',
                        'filters' => null
                    ],
                    (object) [
                        'method' => 'GET',
                        'uri' => '',
                        'params' => [],

                        'action' => 'IndexController@index',
                        'filters' => null
                    ]



        ];

        $filter->matchFilters($routes);

        foreach ($routes as $route) {
            
            $this->assertIsArray($route->filters);
            $this->assertEquals($route->filters, $filterExample);
        }
    }

    /**
     *  test reg ex filter
     * @return void
     */
    public function testFilterWithRegularExpression()
    {
        $filter = new Filter;

        $route = (object) [
                    'method' => 'GET',
                    'uri' => '',
                    'params' => [
                        "id" => 20
                    ],

                    'action' => 'IndexController@index',
                    'filters' => [
                        "id" => "/[0-9]/"
                    ]
                ];

        $test = $filter->filterRegEx($route);
        $this->assertNull($test); // valid regex

        $route->params = ['id' => "abcd"];
        $this->expectException(\Aven\Exception\RegExMisMatchException::class); // mis match reg ex
        $filter->filterRegEx($route);
    }

}