<?php

declare(strict_types=1);

use Swlib\SaberGM;

use function Co\run;

require_once "vendor/autoload.php";

use Swoole\Process;
use Swoole\Coroutine;
use Swoole\Coroutine\Server\Connection;
use Co\Http\Server;
//多进程管理模块
$pool = new Process\Pool(4);
//让每个OnWorkerStart回调都自动创建一个协程
// $pool->set(['enable_coroutine' => true]);
$pool->on('workerStart', function ($pool, $id) {
    //每个进程都监听9501端口
    run(function () use ($id) {
        $server = new Server('0.0.0.0', 9501, false, true);
        $server->handle('/', function ($request, $ws) use ($id) {
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
                        Connect::pop($ws->fd);
                        $ws->close();
                        return;
                    }
                    dump($ws->fd . ':' . $id . ':' . $frame->data);
                }
            }
        });
        $server->start();
    });
});
$pool->start();
