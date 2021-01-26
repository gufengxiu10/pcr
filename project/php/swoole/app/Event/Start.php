<?php

namespace App\Event;

use Anng\lib\App;
use Anng\lib\Container;

class Start
{
    public function __construct(Container $container)
    {
        $container->db->derive()->insert();
    }

    public function run($ws)
    {
        // $this->app->crontab->setWs($ws);
    }
}
