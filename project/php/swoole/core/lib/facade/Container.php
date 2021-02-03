<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Container as LibContainer;
use Anng\lib\Facade;

class Container extends Facade
{
    protected static function getFacadeClass()
    {
        return LibContainer::class;
    }
}
