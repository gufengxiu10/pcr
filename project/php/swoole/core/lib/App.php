<?php

namespace Anng\lib;

use Anng\lib\facade\Config;
use Anng\lib\facade\Crontab;
use Anng\lib\facade\Db;
use Anng\lib\facade\Env;
use Anng\lib\facade\Info;
use Anng\lib\facade\Reflection;
use Anng\lib\facade\Table as FacadeTable;

use function Co\run;
use Co\Http\Server;
use Swoole\Constant;
use Swoole\Process\Manager;
use Swoole\Process\Pool;
use Swoole\Server as SwooleServer;
use Swoole\Table;
use Swoole\Timer;

class App
{
    private $service;

    //容器对象
    private $container;

    //根目录
    protected $rootPath;


    protected $fd;

    public function __construct()
    {
        date_default_timezone_set("Asia/Shanghai");
        $this->rootPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;
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
            Config::load($file, pathinfo($file, PATHINFO_FILENAME));
        }


        //加载ENV文件
        Env::setPath($this->getEnv())->loading();

        //创建共享内存
        FacadeTable::create([
            ['key', Table::TYPE_STRING, 64],
            ['data', Table::TYPE_STRING, 64],
        ]);
    }

    public function start()
    {
        $this->startFuncMap = [
            [function (Pool $pool, $workerId) {
                Timer::tick(1000, function () use ($pool, $workerId) {
                    $process = $pool->getProcess(1);
                    $socket = $process->exportSocket();
                    $socket->send('来自定时任务的消息');
                    // $socket->send("hello proc0\n");
                });
            }, false],
            [function ($pool, $workerId) {
                go(function () use ($pool, $workerId) {
                    $process = $pool->getProcess();
                    $socket = $process->exportSocket();
                    while (true) {
                        $frame =  $socket->recv();
                        dump($frame);
                    }
                });
                $process = $pool->getProcess();
                $this->server($pool, $workerId);
            }, false],
            [function ($pool, $workerId) {
                $process = $pool->getProcess();
                $this->server($pool, $workerId);
            }, false]
        ];
        $this->pool = new Pool(3, SWOOLE_IPC_UNIXSOCK, 0, true);
        $this->init();
        $this->pool->on(Constant::EVENT_WORKER_START, function (Pool $pool, int $workerId) {
            [$func, $enbleCoroutine] = $this->startFuncMap[$workerId];
            $this->pools[] = $pool;
            $func($pool, $workerId);
        });
        $this->pool->start();
    }

    public function start2()
    {
        $pm = new Manager();
        $this->init();
        $pm->add(function (Pool $pool, int $workerId) use ($pm) {
            // $this->crontabStart();
        }, true);

        // $pm->addBatch(3, function (Pool $pool, int $workerId) {
        //     dump($workerId);
        //     $this->server($pool, $workerId);
        // }, true);
        $pm->setIPCType(SWOOLE_IPC_UNIXSOCK);
        $pm->start();
    }

    public function server($pool, $workerId)
    {
        // \Swoole\Coroutine::set([
        //     'hook_flags' => SWOOLE_HOOK_CURL
        // ]);

        // run(function () use ($pool, $workerId) {
        $this->server = new Server('0.0.0.0', 9502, false, true);
        $this->createMysqlPool();
        $this->server->handle('/', function ($request, $ws) use ($pool, $workerId) {
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

                    dump($workerId . '_' . $frame->data);
                    // $this->ico('WebSocket', [
                    //     'ws' => $ws,
                    //     'request' => $request,
                    //     'frame' => $frame,
                    //     'server' => $this->server,
                    //     'pool' => $pool,
                    //     'id' => $workerId,
                    // ]);
                }
            }
        });
        $this->server->start();
        // });
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


    public function ico($method, $argc)
    {
        $className = "\\Anng\\event\\" . $method;
        Reflection::setDefaultMethod('run', $argc)
            ->instance($className, $argc);
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

    public function getFd()
    {
        return $this->fd;
    }
}
