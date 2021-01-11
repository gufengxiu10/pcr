<?php

declare(strict_types=1);

namespace Anng\lib;

use Dotenv\Dotenv;

class Env
{
    protected $app;
    
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @name: env文件加载
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-05 10:48:26
     * @return {*}
     */
    public function loading()
    {
        $dotenv = Dotenv::createMutable(realpath($this->app->getEnv()));
        $dotenv->load();
    }

    /**
     * @name: 获得参数
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-05 10:48:23
     * @return array|string
     */
    public function get(string $name = null)
    {
        if (is_null($name) || empty($name)) {
            return $_ENV;
        } else {
            return $_ENV[$name];
        }
    }
}
