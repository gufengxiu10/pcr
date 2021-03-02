<?php

declare(strict_types=1);

use Swlib\SaberGM;

use function Co\run;

require_once "vendor/autoload.php";

run(function () {
    for ($i = 0; $i < 1500; $i++) {
        go(function () {
            $websocket = SaberGM::websocket('ws://127.0.0.1:9501');
            while (true) {
                // echo $websocket->recv(1) . "\n";
                $websocket->push("hello");
                co::sleep(1);
            }
        });
    }
});
