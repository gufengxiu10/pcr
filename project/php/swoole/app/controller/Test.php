<?php

namespace app\controller;

use Anng\lib\annotations\Cq;

class Test
{
    #[Cq(['key' => '你好'])]
    public function FunctionName()
    {
    }
}
