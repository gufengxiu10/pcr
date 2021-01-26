<?php

namespace Anng\lib;

use ReflectionClass;

class App
{
    private $service;

    //容器对象
    private Container $container;

    //根目录
    protected $rootPath;

    public function __construct(Container $container)
    {
        date_default_timezone_set("Asia/Shanghai");
        $this->rootPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;
        $this->container = $container;
        // $this->init();
    }

    public function init()
    {
        $configPath = $this->getConfigPath();
        //加载配置文件
        $files = [];
        if (is_dir($configPath)) {
            $files = glob($configPath . '*.php');
        }

        foreach ($files as $file) {
            $this->container->config->load($file, pathinfo($file, PATHINFO_FILENAME));
        }
    }

    public function start()
    {
        $this->init();

        $this->service = new \Swoole\WebSocket\Server('0.0.0.0', 9502);
        $this->service->on('start', [$this->ico('Start'), 'run']);
        $this->service->on('open', [$this->ico('Open'), 'run']);
        $this->service->on('message', [$this->ico('Message'), 'run']);
        $this->service->start();
    }


    public function ico($method, ...$argc)
    {
        $className = "\App\Event\\" . $method;
        $object = $this->container->make($className, ...$argc);
        // $reflect = new ReflectionClass($className);
        // $object = $reflect->getConstructor() ? $reflect->newInstanceArgs($argc) : $reflect->newInstanceArgs([]);
        return $object;
    }

    /**
     * @name: 配置目录
     * @author: ANNG
     * @Date: 2021-01-11 09:38:21
     * @return string
     */
    public function getConfigPath(): string
    {
        return $this->rootPath . 'config' . DIRECTORY_SEPARATOR;
    }

    /**
     * @name: Env位置
     * @author: ANNG
     * @Date: 2021-01-11 09:41:40
     * @return string
     */
    public function getEnv()
    {
        return $this->rootPath;
    }
}
