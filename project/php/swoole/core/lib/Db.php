<?php

declare(strict_types=1);


namespace Anng\lib;

use Anng\lib\db\Config;
use Anng\lib\db\connect\PdoPool;
use Anng\lib\db\Sql;

class Db
{
    public $pool;
    public $config;
    protected $sql;

    public function create()
    {
        if (!$this->pool) {
            $this->pool = (new PdoPool($this));
        }
        return $this;
    }

    public function getPool()
    {
        if (!$this->pool) {
            $this->pool = (new PdoPool($this));
        }

        return $this->pool;
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
        if (!$this->config) {
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

    public function getConnection(): Sql
    {
        $connection = $this->getPool()->get();
        return (new Sql($connection, $this->config));
    }

    public function pushConnection(Sql $sql): void
    {
        $this->getPool()->put($sql->getConnection());
        //消毁
        unset($sql);
    }
}
