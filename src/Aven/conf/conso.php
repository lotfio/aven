<?php

/**
 * Aven       Robust PHP Router.
 *
 * @author    Lotfio Lakehal <lotfiolakehal@gmail.com>
 * @copyright 2016 Lotfio Lakehal
 * @license   MIT
 *
 * @link      https://github.com/lotfio/aven
 */

return [

    'APP_NAME'          => 'Aven PHP router',
    'APP_VERSION'       => '0.3.0',
    'APP_RELEASE_DATE'  => '13-09-2019 by lotfio lakehal',

    'DEFAULT_COMMAND'   => 'Info',

    'APP_LOGO_FILE'     => dirname(__DIR__).'/Console/aven.logo',

     'COMMANDS'          => [
        dirname(__DIR__, 3).'/vendor/lotfio/conso/src/Conso/Commands/',
        dirname(__DIR__).'/Console/Commands/',
    ],

    'NAMESPACE' => [
        'Conso\\Commands\\',
        'Aven\\Console\\Commands\\',
    ],
];
