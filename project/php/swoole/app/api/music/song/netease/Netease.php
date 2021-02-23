<?php

declare(strict_types=1);

namespace app\api\music\song\netease;

use app\api\music\Music;


class Netease extends Music
{
    public function getNamespace()
    {
        return '\\app\\api\\music\\song\\netease\\module\\';
    }
}
