<?php

namespace Anng\lib;

use ReflectionClass;

use function Co\run;
use Co\Http\Server;
use Swoole\Coroutine;

class App
{
    private $service;

    //容器对象
    private Container $container;

    //根目录
    protected $rootPath;

    public function __construct(Container $container)
    {
        date_default_timezone_set("Asia/Shanghai");
        $this->rootPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;
        $this->container = $container;
        // $this->init();
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
        $this->init();
        run(function () {
            $this->server = new Server('0.0.0.0', 9502);
            //启动任务调度器
            $this->crontabStart();
            $this->createMysqlPool();
            $this->ico('Test', [$this->container]);
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

                        // $pdo = $this->container->db->getPool()->get();
                        // dump($pdo);
                        dump($ws->fd . ':' . $frame->data);
                        $this->ico('Message', [$ws, $frame]);
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
        $this->container
            ->crontab
            ->setTask($this->container->config->get('crontab'))->run();
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
        $this->container->db
            ->setConfig($this->container->config->get('datebase'))
            ->create();
    }


    public function ico($method, ...$argc)
    {
        $className = "\App\Event\\" . $method;
        $reflect = new ReflectionClass($className);
        $object = $reflect->getConstructor() ? $reflect->newInstanceArgs(...$argc) : $reflect->newInstanceArgs([]);
        return $object;
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
}
