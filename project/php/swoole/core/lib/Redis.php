<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\facade\Config;
use Predis\Client;

class Redis
{

    protected Object $redis;
    protected array $config;

    public function __construct()
    {
        $this->config = Config::get('redis');
        $this->connect();
    }

    /**
     * @name: 实例化
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-12 13:52:17
     * @return {*}
     */
    public function connect(): void
    {
        $this->redis = new Client([
            'host' => $this->config['host'],
            'port' => $this->config['port'],
        ]);

        if (!empty($this->config['auth'])) {
            $this->redis->auth($this->config['auth']);
        }
    }

    public function client()
    {
        return $this->redis;
    }

    public function __call($method, $argc)
    {

        return $this->redis->$method(...$argc);
    }
}
