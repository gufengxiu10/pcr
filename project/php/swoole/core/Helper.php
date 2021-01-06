<?php

use Anng\lib\Env;

if (!function_exists('env')) {
    function env($name = '')
    {
        $env = new Env();
        return $env->get($name);
    }
}
