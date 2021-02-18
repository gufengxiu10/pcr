<?php

declare(strict_types=1);

namespace Anng\lib\db\biluder\sql;

trait Insert
{
    protected $installSql = "INSERT INTO %TABLE%(%FIELD%) VALUES %DATA% %COMMENT%";
    protected $installAllSql = "INSERT INTO %TABLE%(%FIELD%) %DATA% %COMMENT%";

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
            '(' . implode(',', $values) . ')',
            ''
        ], $this->installSql);

        return $sql;
    }

    public function insertAll()
    {
        $data = $this->parseData([], false);
        if (empty($data)) {
            return false;
        }


        $value = [];
        foreach ($data as &$val) {
            $val = $this->parseData($val);
            array_push($value, "(" . implode(',', $val) . ')');
        }

        $field = array_keys(end($data));
        $sql = str_replace(["%TABLE%", "%FIELD%", "%DATA%", "%COMMENT%"], [
            $this->parseTable(),
            implode(',', $field),
            implode(',', $value),
            ''
        ], $this->installSql);
        return $sql;

        // $field = [];

        // $field = !isset($data['field']) ? $data[0] : $data['field'];
        // $nd = !isset($data['data']) ? $data[1] : $data['data'];
        // $values = '';
        // foreach ($nd as $value) {
        //     $values .= 'SELECT ';
        //     foreach ($value as &$val) {
        //         if (is_string($val)) {
        //             $val = "'" . $val . "'";
        //         }
        //     }

        //     $values .= implode(',', $value);
        //     $values .= ' UNION ALL ';
        // }

        // $values = rtrim($values, 'UNION ALL');

        // $sql = str_replace(["%TABLE%", "%FIELD%", "%DATA%", "%COMMENT%"], [
        //     $this->parseTable(),
        //     implode(',', $field),
        //     $values,
        //     ''
        // ], $this->installAllSql);
        return $sql;
    }
}
