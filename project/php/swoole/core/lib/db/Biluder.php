<?php

declare(strict_types=1);

namespace Anng\lib\db;

abstract class Biluder
{

    protected $connection;
    protected array $option = [];

    protected $installSql = "INSERT INTO %TABLE%(%FIELD%) VALUES (%DATA%) %COMMENT%";
    protected $installAllSql = "INSERT INTO %TABLE%(%FIELD%) %DATA% %COMMENT%";
    protected $selectFindSql = "SELECT %FIELD% FROM %TABLE% %WHERE% LIMIT 1";

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

    public function insertAll()
    {
        $data = $this->parseData(false);
        if (empty($data)) {
            return false;
        }

        $field = [];

        $field = !isset($data['field']) ? $data[0] : $data['field'];
        $nd = !isset($data['data']) ? $data[1] : $data['data'];
        $values = '';
        foreach ($nd as $value) {
            $values .= 'SELECT ';
            foreach ($value as &$val) {
                if (is_string($val)) {
                    $val = "'" . $val . "'";
                }
            }

            $values .= implode(',', $value);
            $values .= ' UNION ALL ';
        }

        $values = rtrim($values, 'UNION ALL');

        $sql = str_replace(["%TABLE%", "%FIELD%", "%DATA%", "%COMMENT%"], [
            $this->parseTable(),
            implode(',', $field),
            $values,
            ''
        ], $this->installAllSql);
        return $sql;
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
    protected function parseData($handle = true): array
    {
        $data = $this->connection->data;

        $re = [];
        if ($handle === true) {
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
