<?php

namespace Croe;


class App
{
    private $service;

    public function __construct()
    {
        $this->service = new Swoole\WebSocket\Server('0.0.0.0', 9502);
    }


    public function onOpen($ws, $request)
    {
        # code...
    }

    public function run()
    {
        $this->service->on('open', function ($ws, $request) {
        });
    }
}
