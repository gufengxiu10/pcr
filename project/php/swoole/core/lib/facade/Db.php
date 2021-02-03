<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Facade;

class Db extends Facade
{
    protected static function getFacadeClass()
    {
        return 'Db';
    }
}
