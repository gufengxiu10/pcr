<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Facade;
use Anng\lib\Reflection as LibReflection;

class Reflection extends Facade
{
    protected static function getFacadeClass()
    {
        return LibReflection::class;
    }
}
