<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\db\biluder\sql\Insert;

abstract class Biluder
{

    use Insert;

    protected $connection;
    protected array $option = [];



    protected $selectFindSql = "SELECT %FIELD% FROM %TABLE% %WHERE% LIMIT 1";

    public function __construct($connection)
    {
        $this->connection = $connection;
    }




    /**
     * @name: 
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 16:07:58
     * @return {*}
     */
    public function find()
    {
        $sql = str_replace(["%TABLE%", "%FIELD%", "%WHERE%"], [
            $this->parseTable(),
            $this->parseField(),
            $this->parseWhere(),
        ], $this->selectFindSql);
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
    protected function parseData($data = [], $handle = true): array
    {
        $data = $data ?: $this->connection->data;

        $re = [];
        if ($handle === true) {
            foreach ($data as $key => $value) {
                if (!is_scalar($value)) {
                    $val = json_encode($value);
                } elseif (is_string($value)) {
                    $val = "'" . trim(addslashes($value), '"') . "'";
                } else {
                    $val = $value;
                }

                $re['`' . $key . '`'] = $val;
            }
        } else {
            $re = $data;
        }


        return $re;
    }

    /**
     * @name: where语句分析
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 16:09:46
     * @return {*}
     */
    public function parseWhere()
    {
        $where = $this->connection->where;
        if (empty($where)) {
            return '';
        }

        $sql = "WHERE ";
        $nw = [];
        foreach ($where as $key => $value) {
            if (isset($value[2])) {
                if (in_array($value[1], ['<', '>', '<>', '='])) {
                    $sql .= "(" . implode(' ', $value) . ") AND ";
                } elseif (strtolower($value[1]) == 'like') {
                    $v = is_string($value[1]) ? "'" .  $value[2] . "'" : $value[1];
                    $sql .= "(`{$value[0]}` LIKE {$v}) AND ";
                }
            } elseif (!isset($value[2])) {
                $v = is_string($value[1]) ? "'" .  $value[1] . "'" : $value[1];
                $sql .= "(`{$value[0]}` = {$v}) AND ";
            }
        }

        return rtrim($sql, 'AND ');
    }

    /**
     * @name: where语句分析
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 16:09:46
     * @return {*}
     */
    public function parseField()
    {
        return $this->connection->field;
    }
}
