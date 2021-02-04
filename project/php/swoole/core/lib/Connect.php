<?php

declare(strict_types=1);


namespace Anng\lib;


class Connect
{
    protected $fd = [];

    public function set($fd, $args = [])
    {
        $this->fd[$fd] = $args;
        return $this;
    }

    public function get()
    {
        return $this->fd;
    }

    public function pop($fd)
    {
        unset($this->fd[$fd]);
        return $this;
    }
}
