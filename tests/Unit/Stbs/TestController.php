<?php

namespace Tests\Unit\Stbs;

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

class TestController
{
    public function test($param)
    {
        return "test pass $param";
    }

    public static function testStatic($param)
    {
        return "test pass $param";
    }

    public function formatedOutput()
    {
        return array();
    }

    public function testValid()
    {
        return 'valid route';
    }
}