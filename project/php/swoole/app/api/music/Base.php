<?php

declare(strict_types=1);

namespace app\api\music;

use app\api\music\facade\Cache;
use app\api\music\song\netease\Netease;

class Base
{
    public function test()
    {
        $netease = new Netease;
        $ref = $netease->module('album')->list();
        // $ref = $netease->module('login')->phone();
        // $ref = $netease->module('song')->search('火影', true);

        dump($ref->getBody());
        // dd(dirname(__DIR__));die;
        // $netease = new Song();
        // return $netease->check('1381552460');
        // // return $netease->song('672188');
        // // return $netease->album('77700');
        // return $netease->setDownLoad(true)->url('1381552460');
        // // $login = new Login();
        // // $login->phone();
        // Response
        // $cloud = new Cloud;
        // $cloud->lists();
    }
}
