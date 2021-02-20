<?php

namespace app\event;

use Anng\lib\App;
use Predis\Client;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class Test
{
    public function __construct($frame)
    {
        $this->run($frame);
    }

    public function run($frame)
    {
    }
}
