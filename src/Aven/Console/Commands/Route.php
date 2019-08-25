<?php namespace Aven\Console\Commands;

/**
 * @author    <contact@lotfio.net>
 * @package   Conso PHP Console Creator
 * @version   0.1.0
 * @license   MIT
 * @category  CLI
 * @copyright 2019 Lotfio Lakehal
 * 
 * @time      Generated at 25-07-2019 by conso
 */

use Conso\Command;
use Aven\Facades\Aven;
use Conso\Contracts\CommandInterface;
use Conso\Exceptions\{OptionNotFoundException, FlagNotFoundException,RunTimeException};

class Route extends Command implements CommandInterface
{
    /**
     * command flags
     * 
     * @var array
     */
    protected $flags = [];

    /**
     * cache file name
     *
     * @var string
     */
    protected $cacheFile = "routes.cache";

    /**
     * routes array
     *
     * @var array
     */
    protected $routes;
    /**
     * command description method
     * 	
     * @return string
     */
    protected $description = "Aven route command to cache, clear cache, and list routes.";

    /**
     * command execute method
     * 
     * @param  string $sub
     * @param  array  $options
     * @param  array  $flags
     * @return void
     */
    public function execute(string $sub, array $options, array $flags)
    {
        switch($sub)
        {
            case 'cache' : $this->cacheRoutes();
            break;
            case 'clear' : $this->clearCache();
            break;
            case 'list'  : $this->listRoutes();
            break;
            default: throw new RunTimeException("$sub sub command not recognized !");
        }
    }

    /**
     * check for cache location
     * 
     * @return string
     */
    public function cacheLocation()
    {
        $dir = rtrim(Aven::getConfig('cache'), '/');

        if(!is_dir($dir))
            throw new RunTimeException(" cache location $dir is not a directory");
            
        if(!is_writable($dir))
            throw new RunTimeException("You don't have permissions to cahche routes on this directory $dir !");

        return $dir;
    }

    /**
     * cache routes
     * 
     * @return void
     */
    public function cacheRoutes()
    {
        // check if cache location eexists
        $this->cacheLocation();

        $file = $dir = rtrim(Aven::getConfig('cache'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->cacheFile;

        foreach (Aven::getRoutes() as $route) { // dont cache closures
                
            if($route->action instanceof \Closure)
                throw new RunTimeException("Can not cache Closures ! use controller methods instead.");
        }


        $routes = "<?php return " . var_export(Aven::getRoutes(), true) . ";";
        $file   = fopen($file, "w+");

        fwrite($file, $routes);

        $this->output->writeLn("\n ");
        $this->output->timer();
        $this->output->writeLn("\n");
        $this->output->writeLn("\n Routes cached successfully to $dir directory !\n");
    }

    /**
     * clear cached routes
     *
     * @return void
     */
    public function clearCache()
    {
        $dir  = $this->cacheLocation();

        $file = $dir . DIRECTORY_SEPARATOR . $this->cacheFile;

        if(!is_file($file)) 
            throw new RunTimeException("No cache file was found $file ! please make sure that you have cached your routes !");
        
        unlink($file); // delete file

        $this->output->writeLn("\n cache cleared successfully !\n");
    }

    /**
     * list routes method
     *
     * @return void
     */
    public function listRoutes()
    {
        $tbl = new \Console_Table();

        $tbl->setHeaders(array('ID','METHOD', 'URI', 'PARAMETERS', 'FILTERS', 'ACTION'));
        $id = 1;

        $this->routes = Aven::fromCache($this->cacheFile) ? Aven::fromCache($this->cacheFile) : Aven::getRoutes();
        sort($this->routes);

        if(count($this->routes) == 0)
            throw new RunTimeException("No routes were found !");

        foreach ($this->routes as $route) {

            $param = "";
            if(preg_match_all("/\<.*?\>+/", $route->pattern, $matches)) {
                $param = str_replace('<', '', implode(",", $matches[0]));
                $param = str_replace('>', '', $param);
            }          


            $uri    = preg_replace('/\P<.*?\>+/', NULL, $route->pattern);
            $uri    = preg_replace('/[^a-zA-Z\/]/', NULL, $uri);
            $uri    = (!empty($uri)) ? "/" . trim($uri, "/") : "/";
            $action = ($route->action instanceof \Closure) ? 'Closure': $route->action; 

            $filter  = (isset($route->filters) && !empty($route->filters)) ? implode(',', (array) $route->filters) : '';

            $tbl->addRow([$id,$route->method, $uri, $param, $filter, $action]);
            $id++;
        }

        $this->output->writeLn("\n".$tbl->getTable());
    }

    /**
     * command help method
     * 	
     * @return string
     */
    public function help() { return "Route command help.";}
}