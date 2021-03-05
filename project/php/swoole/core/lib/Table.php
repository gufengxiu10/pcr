<?php

declare(strict_types=1);

namespace Anng\lib;

use Swoole\Table as SwooleTable;

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

    public function set($key, $data, $append = false)
    {
        if ($append === true) {
            $d = $this->table->get($key);
            if (!empty($d)) {
                foreach ($data as $k => &$v) {
                }
            }
        }
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


    public function getinstance()
    {
        return $this->table;
    }
}
