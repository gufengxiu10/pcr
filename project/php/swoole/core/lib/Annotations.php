<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\annotations\contract\BeforeContract;
use Anng\lib\annotations\contract\Contract;
use Anng\lib\annotations\Messages;
use Anng\lib\facade\App;
use ReflectionAttribute;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class Annotations
{
    private array $instances = [];

    /**
     * @name: 加载注解文件
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-11 10:16:03
     * @return {*}
     */
    public function load()
    {
        try {
            $path = App::getRootPath('app/controller');
            $finder = new Finder;
            $finder->in($path);
            foreach ($finder->files()->name('*.php') as $value) {
                $name = $value->getRelativePathname();
                $class = '\\app\\controller\\' . str_replace([
                    '.php', '/'
                ], ['', '\\'], $name);
                $refltion = new ReflectionClass($class);
                foreach ($refltion->getMethods() as $method) {
                    $attr = $method->getAttributes(Contract::class, ReflectionAttribute::IS_INSTANCEOF);
                    foreach ($attr as $val) {
                        $object = $val->newInstance();
                        if ($object instanceof BeforeContract) {
                            if (method_exists($object, 'setMethod')) {
                                $object->setMethod($method);
                            }
                            $this->instances['before'][] = $object;
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
        }

        return $this;
    }

    public function run()
    {
        if (array_key_exists('before', $this->instances)) {
            foreach ($this->instances['before'] as $value) {
                $value->run();
            }
        }

        if (array_key_exists('after', $this->instances)) {
            foreach ($this->instances['after'] as $value) {
                $value->run();
            }
        }
    }
}
