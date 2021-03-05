<?php

declare(strict_types=1);

namespace Anng\lib;

class Info
{
    private $info = [];

    public function set($key, $data, $cid = null)
    {
        if ($cid == null) {
            $cid = getCid();
        }

        $workId = posix_getpid();
        $this->info[$workId . '_' . $cid][$key] = $data;
    }

    /**
     * @name: 获得参数
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-05 10:48:23
     * @return array|string
     */
    public function get(string $key = '', $cid = null)
    {
        if ($cid == null) {
            $cid = getCid();
        }

        $workId = posix_getpid();

        if (empty($key)) {
            return $this->info[$workId . '_' . $cid];
        }

        return $this->info[$workId . '_' . $cid][$key];
    }
}
