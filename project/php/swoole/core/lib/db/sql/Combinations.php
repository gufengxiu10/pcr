<?php

declare(strict_types=1);

namespace Anng\lib\db\sql;

trait Combinations
{
    /**
     * @name: 查询字段组装
     * @param {*} $sql
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 13:59:50
     * @return string
     */
    final protected function fieldCombination(string $sql): string
    {
        if (is_array($this->field)) {
            $this->field = implode(',', $this->field);
        }

        return $sql . ' ' . $this->field;
    }


    /**
     * @name: 查询表组装
     * @param {*} string
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 14:01:16
     * @return {*}
     */
    final protected function tableCombination(string $sql, $type = 'select'): string
    {
        switch ($type) {
            case 'select':
                $sql = $this->tableSelectBination($sql);
                break;
            case 'insert':
                $sql = $this->tableInsertBination($sql);
                break;
            default:
        }
        if ($this->alias) {
            $sql .= ' AS ' . $this->alias;
        }

        return $sql;
    }

    final protected function tableSelectBination(string $sql): string
    {
        $sql . ' ' . $this->table;
        return $sql;
    }

    final protected function tableInsertBination(string $sql): string
    {
        $sql . ' ' . $this->table;
        return $sql;
    }

    /**
     * @name: join组装
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 14:14:20
     * @return {*}
     */
    final protected function joinCombination(string $sql): string
    {
        if ($this->join) {
        }
        return $sql;
    }

    /**
     * @name: where条件组合
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 14:19:44
     * @return {*}
     */
    final protected function whereCombination(string $sql): string
    {
        if ($this->where) {
            $sqlArr = [];
            foreach ($this->where as $val) {
                if (is_string($val)) {
                    $sqlArr[] = $val;
                } elseif (is_array($val)) {
                    if (isset($val[2])) {
                        $sqlArr[] = $val[0] . ' ' . $val[1] . ' ' . $val[2];
                    } else {
                        $sqlArr[] = $val[0] . ' = ' . $val[2];
                    }
                }
            }

            $sql .= ' WHERE ' . implode(' AND ', $sqlArr);
        }
        return $sql;
    }
}
