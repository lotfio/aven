<?php declare(strict_types=1);

/*
 * This file is a part of aven
 *
 * @package     Aven
 * @version     1.0.0
 * @author      Lotfio Lakehal <contact@lotfio.net>
 * @copyright   Lotfio Lakehal 2019
 * @license     MIT
 * @link        https://github.com/lotfio/aven
 *
 */

if (!function_exists('formatOutput')) {
    /**
     * format invoked callbacks output
     *
     * @param  mixed $output
     * @return void
     */
    function formatOutput($output)
    {
        if (gettype($output) == 'array' || gettype($output) == 'object') {
            header('Content-Type: application/json');
            exit(
                json_encode($output)
            );
        }

        echo $output;
    }
}