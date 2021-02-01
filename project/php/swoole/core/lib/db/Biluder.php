<?php

declare(strict_types=1);

namespace Anng\lib\db;

abstract class Biluder
{

    protected $connection;
    protected array $option = [];

    protected $installSql = "INSERT INTO %TABLE%(%FIELD%) VALUES (%DATA%) %COMMENT%";


    public function __construct($connection)
    {
        $this->connection = $connection;
    }


    public function insert()
    {
        $data = $this->parseData();
        if (empty($data)) {
            return false;
        }

        $field = array_keys($data);
        $values = array_values($data);

        $sql = str_replace(["%TABLE%", "%FIELD%", "%DATA%", "%COMMENT%"], [
            $this->parseTable(),
            implode(',', $field),
            implode(',', $values),
            ''
        ], $this->installSql);

        return $sql;
    }

    /**
     * @name: 分析表名
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-01 09:25:06
     * @return {*}
     */
    protected function parseTable(): string
    {
        $sql = $this->connection->table;
        if ($this->connection->alias) {
            $sql .= ' AS ' . $this->connection->alias;
        }
        return $sql;
    }

    /**
     * @name: 数据处理
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-01 10:22:25
     * @return array
     */
    protected function parseData(): array
    {
        $data = $this->connection->data;

        $re = [];
        foreach ($data as $key => $value) {
            if (!is_scalar($value)) {
                $val = json_encode($value);
            } elseif (is_string($value)) {
                $val = "'" . trim($value, '"') . "'";
            } else {
                $val = $value;
            }

            $re['`' . $key . '`'] = $val;
        }

        return $re;
    }
}
