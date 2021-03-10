<?php

namespace app\controller\admin;

use Anng\lib\annotations\Cq;
use Anng\lib\annotations\module\Messages;
use app\controller\Test;

class Admin
{
    #[Messages(['param' => '你好'])]
    public function list()
    {
    }

    #[Test(10)]
    public function ki()
    {
    }
}
