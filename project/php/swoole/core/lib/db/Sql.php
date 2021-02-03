<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\db\biluder\Mysql;
use Swoole\Database\PDOProxy;

class Sql
{
    protected PDOProxy $connection;
    protected $pool;
    protected $biluder;

    //表名
    public string|null $table = null;

    //字段
    public string|array $field = '*';

    //别名
    public string|null $alias = null;

    //条件
    public array $where = [];

    public array $data = [];

    public function __construct(PDOProxy $connection, Config $config)
    {
        $this->connection = $connection;
        $this->config = $config;
        $this->biluder = new Mysql($this);
    }

    public function getConnection()
    {
        return $this->connection;
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
     * @name: 条件
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 16:10:22
     * @return {*}
     */
    public function where($field, $condition, $value = null)
    {
        $where = [$field, $condition, $value];
        array_push($this->where, $where);
        return $this;
    }

    /**
     * @name: 添加
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-29 11:02:52
     * @return {*}
     */
    public  function insert(array $data)
    {
        $this->data = $data;
        $sql = $this->biluder->insert();
        $pdo = $this->pool->get();
        $statement = $pdo->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }
        $this->pool->put($pdo);
        return $result;
    }

    /**
     * @name: 添加并获得ID
     * @param {*} $data
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 10:22:18
     * @return {*}
     */
    public function insertId($data)
    {
        $this->data = $data;
        $sql = $this->biluder->insert();
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }

        $id = $this->connection->lastInsertId();
        $this->clear();
        return $id;
    }

    /**
     * @name: 批量添加
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 11:01:00
     * @return {*}
     */
    public function insertAll(array $data)
    {
        $this->data = $data;
        $sql = $this->biluder->insertAll();
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }
        $this->clear();
        return $result;
    }

    /**
     * @name: 
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-01 09:48:45
     * @return {*}
     */
    public function find()
    {
        $sql = $this->biluder->find();
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }

        $data = $statement->fetch();
        $this->clear();
        return $data;
    }

    private function clear()
    {
        $this->where = [];
        $this->table = null;
        $this->field = '*';
        $this->alias = null;
        $this->data = [];
    }
}
