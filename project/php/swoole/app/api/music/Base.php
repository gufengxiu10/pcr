<?php

declare(strict_types=1);

namespace app\api\music;

use app\api\music\son\Netease;
use app\api\music\son\netease\Login;

class Base
{

    public function test()
    {
        $netease = new Netease();
        return $netease->search('寒蝉');
        // return $netease->song('672188');
        // return $netease->album('77700');
        // return $netease->setDownLoad(true)->searchOnce($key);
        $login = new Login();
        $login->email();
    }
}
