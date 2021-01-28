<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\Db;
use Anng\lib\db\sql\Combinations;
use Exception;

class Sql
{
    use Combinations;

    protected Db $db;
    protected $pool;

    //表名
    protected string|null $table = null;

    //字段
    protected string|array $field = '*';

    //别名
    protected string|null $alias = null;

    public function __construct(Db $db)
    {
        $this->db = $db;
        $this->config = $db->config;
        $this->pool = $this->db->getPool();
    }

    /**
     * @name: 设置表
     * @param {*}
     * @author: ANNG
     * @Date: 2021-01-28 10:41:08
     * @return static
     */
    public function name($val): static
    {
        $this->table = is_null($this->config->get('prefix')) ? $val :  $this->config->get('prefix') . $val;
        return $this;
    }

    /**
     * @name: 设置查询字段
     * @param string|array|bool $val 字段值
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 13:47:17
     * @return {*}
     */
    public function field(string|array|bool $val): static
    {
        if ($val === false) {
            $val = '*';
        }
        $this->field = $val;
        return $this;
    }

    /**
     * @name: 别名
     * @param string $val 别名值
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 13:51:43
     * @return {*}
     */
    public function alias(string $val): static
    {
        $this->alias = $val;
        return $this;
    }

    /**
     * @name: 查询数据
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-26 17:12:50
     * @return {*}
     */
    public function select()
    {
        $pdo = $this->pool->get();

        $sql = $this->selecSqlMake();
        $statement = $pdo->query($sql);
        if (!$statement) {
            throw new Exception('Prepare failed');
        }

        $result = $statement->fetchAll();
        dump($result);
        $this->pool->put($pdo);

        return $result;
    }

    /**
     * @name: 生成sql
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 11:01:18
     * @return {*}
     */
    private function selecSqlMake()
    {
        $sql = 'SELECT';
        $sql = $this->fieldCombination($sql);
        $sql = $this->tableCombination($sql);
        $sql = $this->joinCombination($sql);

        if ($this->alias) {
            $sql .= ' AS ' . $this->alias;
        }

        return $sql;
    }
}
