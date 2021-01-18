<?php

namespace App\Event;

use Anng\lib\App;

class Start
{
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function run($ws)
    {
        $this->app->crontab->setWs($ws);
    }
}
