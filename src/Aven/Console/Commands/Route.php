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
use Conso\Contracts\CommandInterface;
use Conso\Exceptions\{OptionNotFoundException, FlagNotFoundException};

class Route extends Command implements CommandInterface
{
    /**
     * command flags
     * 
     * @var array
     */
    protected $flags = [];

    /**
     * command description method
     * 	
     * @return string
     */
    protected $description = "Route command description.";

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

        // to be replaced with your code
        return $this->output->writeLn("\n\n  Welcome to Route command. \n\n", "yellow");
    }

    /**
     * command help method
     * 	
     * @return string
     */
    public function help() { return "Route command help.";}
}