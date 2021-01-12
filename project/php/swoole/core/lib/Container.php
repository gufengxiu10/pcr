<?php

declare(strict_types=1);

namespace Anng\lib;

use Closure;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class Container implements ContainerInterface
{

    /**
     * @name: 容器中的对象实例
     * @var array
     */
    protected array $instances = [];

    /**
     * @name 容器绑定标识
     * @var array
     */
    protected array $bind = [];

    /**
     * @name: 绑定
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-09 15:17:20
     * @return {*}
     */
    public function bind($abstract, $concrete = null)
    {
        if (is_array($abstract)) {
            foreach ($abstract as $key => $val) {
                $this->bind($key, $val);
            }
        } elseif ($concrete instanceof Closure) {
            //回调函数直接放入容器
            $this->instance($abstract, $concrete);
        } elseif (is_object($concrete)) {
            //对象,实例直接放入容器
            $this->instance($abstract, $concrete);
        } else {
            $abstract = $this->getAlias($abstract);
            if ($abstract != $concrete) {
                //当前标识不等于值,直接放入bind当新的标识
                $this->bind[$abstract] = $concrete;
            }
        }

        return $this;
    }

    /**
     * @name: 根据别名获得真实类名
     * @param {*} string
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-09 15:41:09
     * @return {*}
     */
    public function getAlias(string $abstract): string
    {
        $abstract = ucfirst($abstract);
        //判断当前标识里面是否存在
        if (isset($this->bind[$abstract])) {
            $bind = $this->bind[$abstract];
            if (is_string($bind)) {
                return $this->getAlias($bind);
            }
        }

        return $abstract;
    }

    /**
     * @name: 绑定一个实例到容器
     * @param {*} string
     * @param {*} $instance
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-09 15:22:06
     * @return {*}
     */
    public function instance(string $abstract, $instance)
    {
        $abstract = $this->getAlias($abstract);
        $this->instances[$abstract] = $instance;
        return $this;
    }

    /**
     * @name: 创建实例,存在则返回实例
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-09 14:29:29
     * @return {*}
     */
    public function make(string $abstract, array $vars = [])
    {
        $abstract = $this->getAlias($abstract);

        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $object = $this->inovkeClass($abstract, $vars);
        $this->instances[$abstract] = $object;
        return $object;
    }

    public function inovkeClass($class, $vars = [])
    {
        $reflect = new ReflectionClass($class);

        $constructor = $reflect->getConstructor();
        $args = $constructor ? ($vars ?: [$this]) : [];
        $object = $reflect->newInstanceArgs($args);
        return $object;
    }

    public function get($name)
    {
        return $this->make($name);
    }

    public function has($name)
    {
        return isset($this->instances[$name]);
    }

    public function __get($name)
    {
        return $this->get($name);
    }
}
