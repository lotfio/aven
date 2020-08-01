<?php

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