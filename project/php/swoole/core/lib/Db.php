<?php

declare(strict_types=1);


namespace Anng\lib;

use Anng\lib\db\connect\PdoPool;
use Anng\lib\db\Sql;

class Db
{
    protected App $app;
    protected string $host = '127.0.0.1';
    protected string|null $db = null;
    protected string|int $prot = 3306;
    protected array $instances = [];
    protected $pool;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function derive()
    {
        $this->pool = $this->container->make(PdoPool::class);
        $this->sql = $this->container->make(Sql::class);
        return $this;
    }

    public function setHost(string $val): static
    {
        $this->host = $val;
        return $this;
    }

    public function setDb(string $val): static
    {
        $this->db = $val;
        return $this;
    }

    public function setProt(string $val): static
    {
        $this->prot = $val;
        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function getProt()
    {
        return $this->prot;
    }

    public function getPool()
    {
        return $this->pool;
    }

    public function __call($method, $args)
    {
        if (method_exists($this->pool, $method)) {
            call_user_func_array([$this->pool, $method], $args);
        } elseif (method_exists($this->sql, $method)) {
            call_user_func_array([$this->sql, $method], $args);
        }

        dump(1);
    }
}
