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

use Aven\Facades\Aven;

class Cli
{
    /**
     * routes collection.
     *
     * @var array
     */
    public $routes;

    /**
     * cli parameters.
     *
     * @var array
     */
    public $commands;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->routes = Aven::fromCache('routes.cache') ? Aven::fromCache('routes.cache') : Aven::getRoutes();

        $this->setCliCommands(); // init commands
    }

    /**
     * run cli app method.
     *
     * @return void
     */
    public function run()
    {
        if (empty($this->commands)) {
            $this->hiAven(); // set logo
            return;
        }

        $this->wants($this->commands[0]);
    }

    /**
     * set cli params.
     *
     * @return void
     */
    public function setCliCommands()
    {
        global $argv;
        unset($argv[0]);

        $this->commands = array_values($argv);
    }

    /**
     * hi message.
     *
     * @return void
     */
    public function hiAven()
    {
        $h = "    ___                      ____              __
   /   |_   _____  ____     / __ \____  __  __/ /____  _____
  / /| | | / / _ \/ __ \   / /_/ / __ \/ / / / __/ _ \/ ___/
 / ___ | |/ /  __/ / / /  / _, _/ /_/ / /_/ / /_/  __/ /    
/_/  |_|___/\___/_/ /_/  /_/ |_|\____/\__,_/\__/\___/_/ \n\n";

        $this->writeLn($h);
        $this->writeLn("Available commands : \n\n");
        $this->writeLn("\033[0;32m - routes      \033[0m  Show defined routes \n");
        $this->writeLn("\033[0;32m - cache       \033[0m  Cache routes and load from cache \n");
        $this->writeLn("\033[0;32m - cache:clear \033[0m  Clear cache and load from routes file \n\n");

        $this->writeLn("How To Use it : \n\n");

        $this->writeLn("\033[0;36m - php aven command \033[0m \n");
        $this->writeLn("\033[0;36m - ./aven command \033[0m \n\n");
    }

    /**
     * write to console output method.
     *
     * @param string $line
     *
     * @return mixed
     */
    public function writeLn($line)
    {
        return fwrite(STDOUT, $line);
    }

    /**
     * check requested command.
     *
     * @param string $command
     *
     * @return void
     */
    public function wants($command)
    {
        switch ($this->commands[0]) {

        case 'cache':       $this->cache();
            break;
        case 'cache:clear': $this->clearCache();
            break;
        case 'routes':      $this->listRoutes();
            break;

        default:
            exit($this->noCommand()); break;
        }
    }

    /**
     * no command match.
     *
     * @return void
     */
    public function noCommand()
    {
        $this->writeLn("\033[0;31m Command {$this->commands[0]} not found ! \033[0m \n\n");
    }

    /**
     * cache routes.
     *
     * @return void
     */
    public function cache()
    {
        $dir = $this->checkLocation();

        $file = $dir.'/routes.cache';

        foreach ($this->routes as $route) { // dont cache closures

            if ($route->action instanceof \Closure) {
                $this->writeLn("\033[0;31mCan not cache Closures ! use controller methods instead. \033[0m \n\n");
                exit(0);
            }
        }

        $file = fopen($file, 'w+');
        fwrite($file, json_encode($this->routes));

        $this->writeLn('Caching Routes ! ');
        $this->waiter();
        $this->writeLn("\n\n\033[0;32mRoutes cached successfully to $dir directory ! \033[0m \n\n");
    }

    /**
     * clear caching.
     *
     * @return void
     */
    public function clearCache()
    {
        $dir = $this->checkLocation();

        $files = glob($dir.'/*'); // get all files

        foreach ($files as $file) { // iterate files

            if (is_file($file)) {
                unlink($file); // delete file
            }
        }

        $this->writeLn('Clearing cache ! ');
        $this->waiter();
        $this->writeLn("\n\n\033[0;32mCache cleared successfully !\033[0m \n\n");
    }

    /**
     * list routes method.
     *
     * @return void
     */
    public function listRoutes()
    {
        $tbl = new \Console_Table();

        $tbl->setHeaders(['ID', 'METHOD', 'URI', 'PARAMETERS', 'FILTERS', 'ACTION']);
        $id = 1;
        sort($this->routes);

        foreach ($this->routes as $route) {
            $param = '';
            if (preg_match_all("/\<.*?\>+/", $route->pattern, $matches)) {
                $param = str_replace('<', '', implode(',', $matches[0]));
                $param = str_replace('>', '', $param);
            }

            $uri = str_replace('(?P<', '', $route->pattern);
            $uri = str_replace('>.*)', '', $uri);
            $uri = str_replace('#', '', $uri);
            $uri = str_replace('^', '', $uri);
            $uri = str_replace('$', '', $uri);

            $uri = (!empty($uri)) ? $uri : '/';
            $action = ($route->action instanceof \Closure) ? 'Closure' : $route->action;
            $filter = (isset($route->filters) && !empty($route->filters)) ? implode(',', (array) $route->filters) : '';

            $tbl->addRow([$id, $route->method, $uri, $param, $filter, $action]);
            $id++;
        }

        $this->writeLn($tbl->getTable()."\n");
    }

    /**
     * waiter method.
     *
     * @return void
     */
    public function waiter()
    {
        for ($i = 0; $i < 20; $i++) {
            $this->writeLn('.');
            usleep(10000);
        }
    }

    /**
     * check location method.
     *
     * @return string
     */
    public function checkLocation()
    {
        $dir = rtrim(Aven::getConfig('cache'), '/');

        if (!is_dir($dir)) {
            $this->writeLn("\033[0;31mDirectory $dir not found ! \033[0m\n\n");
            exit(0);
        }
        if (!is_writable($dir)) {
            $this->writeLn("\033[0;31mYou don't have permissions to cahche routes on this directory $dir !\033[0m\n\n");
            exit(0);
        }

        return $dir;
    }
}
