<?php

use Anng\lib\Env;
use Swoole\Coroutine;

if (!function_exists('env')) {
    function env($name = '')
    {
        // $env = new Env();
        // return $env->get($name);
    }
}


if (!function_exists('getCid')) {
    function getCid()
    {
        $id = Coroutine::getCid();
        while (Coroutine::getPcid($id) > 1) {
            $id = Coroutine::getPcid($id);
        }

        return $id;
    }
}
