<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\Db;
use Exception;
use Swoole\Coroutine;

class Sql
{
    protected Db $db;
    protected $pool;

    public function __construct(Db $db)
    {
        $this->db = $db;
        $this->pool = $this->db->getPool();
    }

    /**
     * @name: 添加数据
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-26 17:12:50
     * @return {*}
     */
    public function insert()
    {
        $pdo = $this->pool->get();
        $statement = $pdo->prepare("SELECT * FROM pixiv_t");
        if (!$statement) {
            throw new Exception('Prepare failed');
        }

        $result = $statement->execute();
        $result = $statement->fetchAll();
        $this->pool->put($pdo);
        return $result;
    }
}
