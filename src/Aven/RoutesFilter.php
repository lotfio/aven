<?php 

declare(strict_types=1);

namespace Aven;

// this class applies reg ex default and user defined to parameters 
class RoutesFilter
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
    public function applyFilters(array &$routes) : void
    {
        $uri  = '';

        foreach($routes as &$route)
        {
            $uri  = '/' . trim($route['GROUP'] . $route['REGEX_URI'], '/'); // append group
            $uri  = str_replace('//', '/', $uri);  // fix if duplicate forwardslashes
            $uri  = str_replace('/', '\\/', $uri); // escape
            
            // user defined regex (from regex method)
            if(is_array($route['PARAMS_REGEX']) && count($route['PARAMS_REGEX']) > 0)
            {
                foreach($route['PARAMS_REGEX'] as $key => $val)
                {
                    $pattern = '~(\('. trim($key, '/') .'\))|(\{'. trim($key, '/') .'\})~';
                    $uri     = preg_replace($pattern, '(' . $val . ')', $uri);
                }
            }

            // apply custom regex
            $uri = $this->replacePredefined(self::PREDEFINED_FILTERS, $uri);

            $uri = "~^" . $uri . "$~"; 

            $route['REGEX_URI'] =  $uri; // ready
        }
    }


    /**
     * regex filter method
     * 
     * should be named after what itis doing it is replacing and addding + by default
     *
     * @param string $pattern
     * @param string $rep
     * @param string $subject
     * @return void
     */
    private function replacePredefined(array $patterns, string $subject)
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
}