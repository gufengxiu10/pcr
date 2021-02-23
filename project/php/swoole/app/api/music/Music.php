<?php

declare(strict_types=1);

namespace app\api\music;

use ReflectionClass;
use ReflectionException;

abstract class Music
{
    private ReflectionClass $refection;

    public function module(string $module)
    {
        $class = $this->getNamespace() . ucfirst($module);
        try {
            $this->refection = new ReflectionClass($class);
            return $this;
        } catch (ReflectionException $e) {
            dump($e->getMessage());
        }
    }

    abstract function getNamespace();

    public function __call($method, $args)
    {
        if ($this->refection->hasMethod($method)) {
            $instance = $this->refection->newInstance();
            return $instance->$method(...$args);
        } else {
            return '方法不存在';
        }
    }
}
