<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\facade\App;
use Dotenv\Dotenv;

class Env
{
    public function __construct()
    {
        $this->loading();
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
        $dotenv = Dotenv::createMutable(realpath(App::getEnv()));
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
