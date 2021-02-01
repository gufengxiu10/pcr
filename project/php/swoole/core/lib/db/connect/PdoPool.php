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
        dump($this->db->config);
        $this->pool = new SwoolePdoPool((new PDOConfig)
                ->withHost($this->db->config->get('host'))
                ->withPort($this->db->config->get('port'))
                ->withDbName($this->db->config->get('name'))
                ->withCharset($this->db->config->get('char'))
                ->withUsername($this->db->config->get('username'))
                ->withPassword($this->db->config->get('password'))
        );

        return $this;
    }

    public function get()
    {
        $conntion = $this->pool->get();
        return $conntion;
    }

    public function put($pdo)
    {
        return $this->pool->put($pdo);
    }
}
