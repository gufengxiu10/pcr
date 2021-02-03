<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Facade;

class Redis extends Facade
{
    protected static function getFacadeClass()
    {
        return 'Redis';
    }
}
