<?php

declare(strict_types=1);

namespace app\api\music;

use app\api\music\son\Netease;

class Base
{

    public function test($key)
    {
        $netease = new Netease();
        // return $netease->search('寒蝉');
        // return $netease->song('672188');
        // return $netease->album('77700');
        return $netease->setDownLoad(true)->searchOnce($key);
    }
}
