<?php

declare(strict_types=1);

use Swlib\SaberGM;
use Swoole\Process;
use Swoole\Table;
use Co\Http\Server;
use function Co\run;

require_once "vendor/autoload.php";

//创建Server对象，监听 127.0.0.1:9501 端口
$server = new Swoole\Server('127.0.0.1', 9502);
$server->set([
    'worker_num' => 5
]);
//监听连接进入事件
$server->on('Connect', function ($server, $fd) {
    echo "Client: Connect.\n";
});

//监听数据接收事件
$server->on('Receive', function ($server, $fd, $reactor_id, $data) {
    $server->send($fd, "Server: {$data}");
});

//监听连接关闭事件
$server->on('Close', function ($server, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$server->start();


run(function () {
    for ($i = 0; $i < 5; $i++) {
        go(function () use ($i) {
            $websocket = SaberGM::websocket('ws://127.0.0.1:9502');
            $websocket->push('110');
            while (true) {
                // echo $websocket->recv(1) . "\n";
                // $websocket->push("hello");
                co::sleep(1);
            }
        });
    }
});
