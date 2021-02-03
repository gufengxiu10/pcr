<?php

declare(strict_types=1);

namespace Anng\lib;

use Exception;
use ReflectionClass;
use ReflectionMethod;

class Reflection
{
    private array $method = [];

    private Container $container;

    private array|null $defaultMethod = null;

    /**
     * @name: 实例化
     * @param string $class 实例化的类名
     * @param array $args 构造函数实例化相关参数
     * @var $reflection 反射实例
     * @var $argc 构造函数相关参数
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-03 09:23:23
     */
    public function instance($class, $args = [], $callMethod = true): object
    {
        $reflection = new ReflectionClass($class);
        $args = [];
        if ($construct = $reflection->getConstructor()) {
            $args = $this->parseData($construct, $args);
        }

        $object = $reflection->newInstanceArgs($args);

        $isMethod = false;
        if ($callMethod === true) {
            foreach ($this->method as $value) {
                if ($reflection->hasMethod($value['method'])) {
                    $args = $value['args'] ?? [];
                    $methodArgs = $this->parseData($reflection->getMethod($value['method']), (array)$args);
                    call_user_func_array([$object, $value['method']], $methodArgs);
                    $isMethod = true;
                }
            }
        }

        if ($isMethod === false && $this->defaultMethod) {
            if ($reflection->hasMethod($this->defaultMethod['method'])) {
                $args = $this->defaultMethod['args'] ?? [];
                $methodArgs = $this->parseData($reflection->getMethod($this->defaultMethod['method']), (array)$args);
                call_user_func_array([$object, $this->defaultMethod['method']], $methodArgs);
            }
        }

        return $object;
    }

    /**
     * @name: 参数解析
     * @param {*} Type
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-03 09:29:11
     */
    private function parseData(ReflectionMethod $refl, array $args = []): array
    {
        $params = $refl->getParameters();
        if (empty($params)) {
            return [];
        }

        $data = [];
        //重置数组指针
        reset($args);
        //用于判断数组键值是以自然数为键,如果是则按顺序赋值
        $type = key($args) === 0 ? 1 : 0;

        foreach ($params as $value) {
            $paramName = $value->getName();
            if (!is_null($value->getType())) {
                $name = $value->getType()->getName();
                //TODO::未处理匿名数据的回调
                if (isset($args[$paramName])) {
                    $data[] = $args[$paramName];
                } else {
                    if ($this->container) {
                        $data[] = $this->container->get($name);
                    } else {
                        $data[] = $this->instance($name, $args, false);
                    }
                }
            } else {
                if ($type == 1 && !empty($args)) {
                    $data[] = array_shift($args);
                } elseif ($type == 0 && isset($args[$paramName])) {
                    $data[] = $args[$paramName];
                } elseif ($value->isDefaultValueAvailable()) {
                    $data[] = $value->getDefaultValue();
                } else {
                    throw new Exception('method param miss:' . $value->getName());
                }
            }
        }

        return $data;
    }

    /**
     * @name: 实例化后自动调用的方法
     * @param string|array $method 调用的方法
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-03 10:44:29
     */
    public function setMethod(string|array $method, array $args = []): static
    {
        if (!empty($this->method)) {
            $this->method = [];
        }

        if (is_string($method)) {
            $this->method[] = [
                'method' => $method,
                'args' => $args
            ];
        } else {
            $this->method = $method;
        }

        return $this;
    }

    /**
     * @name: 设置默认的调用方法
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-03 10:53:22
     * @return {*}
     */
    public function setDefaultMethod(string $method, array $args = []): static
    {
        $this->defaultMethod = [
            'method' => $method,
            'args' => []
        ];
        return $this;
    }

    public function setContainer(Container $container): static
    {
        $this->container = $container;
        return $this;
    }
}
