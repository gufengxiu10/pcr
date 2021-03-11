<?php

namespace app\controller\admin;

use Anng\lib\annotations\module\Cq;
use Anng\lib\annotations\module\Messages;
use app\controller\Test;

class Admin
{
    #[Messages(['key' => '你好', 'alias' => ['wq', 'find']])]
    public function list()
    {
        dump('list1');
    }

    #[Messages(['key' => '你不好', 'alias' => ['ki']])]
    public function list2()
    {
        dump('list2');
    }

    public function list3()
    {
        dump('list3');
    }
}
