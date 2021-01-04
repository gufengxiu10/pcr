<?php

namespace Anng;

use ReflectionClass;

class App
{
    private $service;

    public function start()
    {
        $this->service = new \Swoole\WebSocket\Server('0.0.0.0', 9502);
        $this->service->on('open', [$this->ico('Open'), 'run']);
        $this->service->on('message', [$this->ico('Message'), 'run']);
        $this->service->start();
    }

    public function ico($method, ...$argc)
    {
        $className = "\App\Event\\" . $method;
        $class = new ReflectionClass($className);
        return $class->newInstanceArgs($argc);
    }
}
