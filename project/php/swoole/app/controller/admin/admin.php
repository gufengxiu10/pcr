<?php

namespace app\controller\admin;

use Anng\lib\annotations\Cq;
use app\controller\Test;

class Admin
{
    #[Cq(['param' => 100])]
    public function list()
    {
    }

    #[Test(10)]
    public function ki()
    {
    }
}
