<?php

declare(strict_types=1);
require_once "vendor/autoload.php";

date_default_timezone_set("Asia/Shanghai");

use Swoole\Coroutine;
use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;
use Swoole\Runtime;


//创建WebSocket Server对象，监听0.0.0.0:9502端口
$ws = new Swoole\WebSocket\Server('0.0.0.0', 9502);

//监听WebSocket连接打开事件
$ws->on('start', function () {
    $pool = new PDOPool((new PDOConfig)
            ->withHost('bj-cdb-ozdvjhny.sql.tencentcdb.com')
            ->withPort(60977)
            ->withDbName('pixiv')
            ->withCharset('utf8mb4')
            ->withUsername('gufengxiu10')
            ->withPassword('Freedomx102')
    );

    Coroutine::create(function () use ($pool) {
        dump($pool);
        $pdo = $pool->get();

        $statement = $pdo->prepare('SELECT ? + ?');
        if (!$statement) {
            throw new RuntimeException('Prepare failed');
        }
        $a = mt_rand(1, 100);
        $b = mt_rand(1, 100);
        $result = $statement->execute([$a, $b]);
        if (!$result) {
            throw new RuntimeException('Execute failed');
        }
        $result = $statement->fetchAll();
        if ($a + $b !== (int)$result[0][0]) {
            throw new RuntimeException('Bad result');
        }
        $pool->put($pdo);
    });
});

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    var_dump($request->fd, $request->server);
    $ws->push($request->fd, "hello, welcome\n");
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    echo "Message: {$frame->data}\n";
    $ws->push($frame->fd, "server: {$frame->data}");
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();

// const N = 1024;

// Runtime::enableCoroutine();
// $s = microtime(true);
// Coroutine\run(function () {
//     $pool = new PDOPool((new PDOConfig)
//             ->withHost('bj-cdb-ozdvjhny.sql.tencentcdb.com')
//             ->withPort(60977)
//             ->withDbName('pixiv')
//             ->withCharset('utf8mb4')
//             ->withUsername('gufengxiu10')
//             ->withPassword('Freedomx102')
//     );
//     for ($n = N; $n--;) {
//         Coroutine::create(function () use ($pool) {
//             $pdo = $pool->get();
//             dump($pdo);
//             $statement = $pdo->prepare('SELECT ? + ?');
//             if (!$statement) {
//                 throw new RuntimeException('Prepare failed');
//             }
//             $a = mt_rand(1, 100);
//             $b = mt_rand(1, 100);
//             $result = $statement->execute([$a, $b]);
//             if (!$result) {
//                 throw new RuntimeException('Execute failed');
//             }
//             $result = $statement->fetchAll();
//             if ($a + $b !== (int)$result[0][0]) {
//                 throw new RuntimeException('Bad result');
//             }
//             $pool->put($pdo);
//         });
//     }
// });
// $s = microtime(true) - $s;
// echo 'Use ' . $s . 's for ' . N . ' queries' . PHP_EOL;
