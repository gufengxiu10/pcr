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
    }

    public function connect()
    {
        $this->redis = $this->app->config->make(Client::class, [
            'host' => $this->config['host'],
            'port' => $this->config['port'],
        ]);

        if (!empty($this->config['auth'])) {
            $this->redis->auth($this->config['auth']);
        }

        return $this;
    }
}
