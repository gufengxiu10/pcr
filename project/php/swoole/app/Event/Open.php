<?php

namespace App\Event;

use Anng\lib\App;
use Hyperf\Crontab\Crontab;

class Open
{
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function run($ws, $request)
    {
    }
}
