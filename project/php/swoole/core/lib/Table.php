<?php

declare(strict_types=1);

namespace Anng\lib;

use Swoole\Table as SwooleTable;

use function Co\run;

class Table
{
    private SwooleTable $table;
    private $columns;

    public function create($columns)
    {
        $this->columns = $columns;
        $this->table = new SwooleTable(1024);
        foreach ($columns as $column) {
            $this->table->column($column[0], $column[1], $column[2]);
        }
        $this->table->create();
        return $this;
    }

    public function set($key, $data)
    {
        return $this->table->set($key, $data);
    }

    public function get($key)
    {
        return $this->table->get($key);
    }

    public function exists($key)
    {
        return $this->table->exists($key);
    }

    public function del($key)
    {
        return $this->table->del($key);
    }
}
