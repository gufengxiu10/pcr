<?php

declare(strict_types=1);


class Predis
{

    protected $instance = null;

    private function __construct()
    {
        # code...
    }

    /**
     * @name: 创建连接
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-18 15:03:51
     * @return {*}
     */
    public function create()
    {
        if (empty($this->instance)) {
            $this->instance = new static;
        }

        return $this->instance;
    }
}
