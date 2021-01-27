<?php

declare(strict_types=1);
require_once "vendor/autoload.php";


Co\run(function () {
    $client = new Swoole\Coroutine\Http\Client("127.0.0.1", 9502);
    $ret = $client->upgrade("/");
    if ($ret) {
        while (true) {
            $client->push("hello");
            var_dump($client->recv());
            co::sleep(5);
        }
    }
});
