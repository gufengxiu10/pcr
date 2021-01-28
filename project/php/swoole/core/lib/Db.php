<?php

declare(strict_types=1);


namespace Anng\lib;

use Anng\lib\db\Config;
use Anng\lib\db\connect\PdoPool;
use Anng\lib\db\Sql;

class Db
{
    protected App $app;
    protected string $host = '127.0.0.1';
    protected string|null $db = null;
    protected string|int $prot = 3306;
    protected array $instances = [];
    public $pool;
    public $config;

    public function create()
    {
        $this->pool = (new PdoPool($this));
        $this->sql = (new Sql($this));
        return $this;
    }

    /**
     * @name: 设置数据库信息
     * @param {*} string
     * @param {*} string
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 10:07:57
     * @return {*}
     */
    public function setConfig(string|array $key, string|null $val = null): static
    {
        if (is_null($this->config)) {
            $this->config = new Config();
        }

        if (!is_null($val)) {
            $this->config->set($key, $val);
        } elseif (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->config->set($k, $v);
            }
        }
        return $this;
    }

    public function getPool()
    {
        return $this->pool;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->sql, $method], $args);
    }
}
