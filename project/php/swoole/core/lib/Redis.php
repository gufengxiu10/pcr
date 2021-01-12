<?php

declare(strict_types=1);

namespace Anng\lib;

use Predis\Client;

class Redis
{

    protected App $app;
    protected Object $redis;
    protected array $config;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->config = $this->app->config->get('redis');
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
        $this->redis = $this->app->make(Client::class, [[
            'host' => $this->config['host'],
            'port' => $this->config['port'],
        ]]);

        // $this->redis = new Client([
        //     'host' => $this->config['host'],
        //     'port' => $this->config['port'],
        // ]);

        if (!empty($this->config['auth'])) {
            $this->redis->auth($this->config['auth']);
        }
    }

    public function __call($method, $argc)
    {
        return $this->redis->$method(...$argc);
    }
}
