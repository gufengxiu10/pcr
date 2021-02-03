<?php

declare(strict_types=1);

namespace Anng\lib;

abstract class Facade
{
    /**
     * @name: 获得对象
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-03 11:56:17
     * @return {*}
     */
    protected static function getInstance(string $class = '', array $args = [])
    {
        $class = $class ?: static::class;
        $facadeClass = static::getFacadeClass();
        if ($facadeClass) {
            $class = $facadeClass;
        }

        return Container::getInstance()->make($class, $args);
    }

    abstract protected static function getFacadeClass();

    public static function __callStatic($method, $argc)
    {
        return call_user_func_array([static::getInstance(), $method], $argc);
    }
}
