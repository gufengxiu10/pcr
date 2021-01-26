<?php

declare(strict_types=1);

namespace Anng\lib;

class Facade
{

    public static function __callStatic($method, $argc)
    {
        // return Container::getInstance()
    }
}
