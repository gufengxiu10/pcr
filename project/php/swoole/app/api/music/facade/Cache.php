<?php

declare(strict_types=1);

namespace app\api\music\facade;

use app\api\music\Cache as MusicCache;
use app\api\music\Facade;

class Cache extends Facade
{
    protected static function getClass()
    {
        return MusicCache::class;
    }
}
