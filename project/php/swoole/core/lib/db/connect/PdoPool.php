<?php

declare(strict_types=1);


namespace Anng\lib\db\connect;

use Anng\lib\Db;
use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool as SwoolePdoPool;

class PdoPool
{
    protected object $pool;
    protected Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
        $this->createDb();
    }

    private function createDb()
    {
        // $this->pool = new SwoolePdoPool((new PDOConfig)
        //         ->withHost($this->db->config->host)
        //         ->withPort(60977)
        //         ->withDbName('pixiv')
        //         ->withCharset('utf8mb4')
        //         ->withUsername('gufengxiu10')
        //         ->withPassword('Freedomx102')
        // );

        $this->pool = new SwoolePdoPool((new PDOConfig)
                ->withHost($this->db->config->host)
                ->withPort($this->db->config->port)
                ->withDbName($this->db->config->name)
                ->withCharset($this->db->config->char)
                ->withUsername($this->db->config->username)
                ->withPassword($this->db->config->password)
        );
        return $this->pool;
    }

    public function get()
    {
        return $this->pool->get();
    }

    public function put($pdo)
    {
        return $this->pool->put($pdo);
    }
}
