<?php

declare(strict_types=1);


namespace Anng\lib\db;


class Config
{
    public string $host = '127.0.0.1';
    public string|int $port = 3306;
    public string|null $username = null;
    public string|null $password = null;
    public string|null $name = null;
    public string $char = 'utf8mb4';
    protected string|null $prefix = null;

    public function set($key, $val): static
    {
        $key = str_replace('db_', '', $key);
        $this->$key = $val;
        return $this;
    }

    /**
     * @name: 获得参数
     * @param {*} $key
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 10:46:35
     * @return mixed
     */
    public function get($key): mixed
    {
        if (!isset($this->$key)) {
            return null;
        }

        return $this->$key;
    }
}
