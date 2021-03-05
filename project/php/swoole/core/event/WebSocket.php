<?php

namespace Anng\event;

use Anng\lib\facade\Container;
use Anng\lib\facade\Reflection as LibReflection;
use Anng\lib\facade\Table;
use app\event\WebSocket as EventWebSocket;

class WebSocket
{
    public function run()
    {
        LibReflection::instance(EventWebSocket::class);
    }
}
