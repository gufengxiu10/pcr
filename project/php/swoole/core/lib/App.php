<?php

namespace Anng\lib;

use Anng\lib\facade\Config;
use Anng\lib\facade\Connect;
use Anng\lib\facade\Container;
use Anng\lib\facade\Crontab;
use Anng\lib\facade\Db;
use Anng\lib\facade\Env;
use Anng\lib\facade\Reflection;
use ReflectionClass;

use function Co\run;
use Co\Http\Server;
use Swoole\Process;
use Swoole\Process\Manager;
use Swoole\Process\Pool;
use Swoole\Table;

class App
{
    private $service;

    //容器对象
    private $container;

    //根目录
    protected $rootPath;

    public function __construct()
    {
        date_default_timezone_set("Asia/Shanghai");
        $this->rootPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;
        $this->container = Container::getInstance();
    }


    public function init()
    {
        $configPath = $this->getConfigPath();
        //加载配置文件
        $files = [];
        if (is_dir($configPath)) {
            $files = glob($configPath . '*.php');
        }

        foreach ($files as $file) {
            $this->container->config->load($file, pathinfo($file, PATHINFO_FILENAME));
        }
    }

    public function start()
    {
        $table = $this->createTable();
        $pm = new Manager();
        $this->init();

        $pm->add(function () {
            run(function () {
                $this->crontabStart();
            });
        });

        $pm->addBatch(2, function (Pool $pool, int $workerId) use ($table) {
            $this->server($table);
        });

        $pm->start();
    }

    public function server($table)
    {
        \Swoole\Coroutine::set([
            'hook_flags' => SWOOLE_HOOK_CURL
        ]);

        run(function () use ($table) {
            $this->server = new Server('0.0.0.0', 9501, false, true);
            $this->createMysqlPool();
            $this->server->handle('/', function ($request, $ws) {
                $ws->upgrade();
                while (true) {
                    $frame = $ws->recv();
                    if ($frame === '') {
                        $ws->close();
                        break;
                    } else if ($frame === false) {
                        echo "error : " . swoole_last_error() . "\n";
                        break;
                    } else {
                        if ($frame->data == 'close' || get_class($frame) === Swoole\WebSocket\CloseFrame::class) {
                            $ws->close();
                            return;
                        }
                        $this->ico('WebSocket', [$ws]);
                    }
                }
            });
            $this->server->start();
        });
    }

    /**
     * @name: 启动任务调度器
     * @author: ANNG
     * @Date: 2021-01-27 15:39:46
     * @return {*}
     */
    public function crontabStart(): void
    {
        Crontab::setTask(Config::get('crontab'))
            ->run();
    }

    /**
     * @name: 创建Mysql连接池
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-27 15:41:03
     * @return {*}
     */
    public function createMysqlPool()
    {
        Db::setConfig(Config::get('datebase'))
            ->create();
    }


    public function ico($method, ...$argc)
    {
        $className = "\\Anng\\event\\" . $method;
        Reflection::setDefaultMethod('run')
            ->instance($className);
    }

    public function createTable()
    {
        $table = new Table(1024);
        $table->column('id', Table::TYPE_INT, 4);
        $table->column('fd', Table::TYPE_INT, 64);
        $table->create();
        return $table;
    }

    /**
     * @name: 配置目录
     * @author: ANNG
     * @Date: 2021-01-11 09:38:21
     * @return string
     */
    public function getConfigPath(): string
    {
        return $this->rootPath . 'config' . DIRECTORY_SEPARATOR;
    }

    /**
     * @name: Env位置
     * @author: ANNG
     * @Date: 2021-01-11 09:41:40
     * @return string
     */
    public function getEnv()
    {
        return $this->rootPath;
    }

    public function getRootPath()
    {
        return $this->rootPath;
    }
}
