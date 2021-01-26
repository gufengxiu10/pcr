<?php

declare(strict_types=1);


namespace Anng\lib;


class Config
{
    protected $app;
    protected $config = [];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function load(string $file, string $name = '')
    {
        $config = [];
        if (is_file($file)) {
            $config = include $file;
        }

        if (!empty($name)) {
            $this->config[$name] = $config;
        } else {
            $this->config = array_merge($this->config, $config);
        }

        return $this->config;
    }

    public function get($name = '')
    {
        if (empty($name)) {
            return $this->config;
        }

        return $this->config[$name];
    }

    public function __get($name)
    {
        return $this->get($name);
    }
}
