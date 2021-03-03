<?php

declare(strict_types=1);

use Swlib\SaberGM;
use Swoole\Process;
use Swoole\Table;
use Co\Http\Server;
use function Co\run;

require_once "vendor/autoload.php";


run(function () {
    for ($i = 0; $i < 5; $i++) {
        go(function () use ($i) {
            $websocket = SaberGM::websocket('ws://127.0.0.1:9501');
            if ($i == 1) {
                $websocket->push('109');
            } else if ($i == 2) {
                $websocket->push('110');
            }
            while (true) {

                // echo $websocket->recv(1) . "\n";
                // $websocket->push("hello");
                co::sleep(1);
            }
        });
    }
});
