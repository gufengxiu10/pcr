<?php

require_once "vendor/autoload.php";

use Swlib\SaberGM;

go(function () {
    $websocket = SaberGM::websocket('ws://172.200.1.3:9503');
    while (true) {
        $data = [
            "action" => "get_login_info"
        ];
        $websocket->push(json_encode($data, JSON_UNESCAPED_UNICODE));
        echo $websocket->recv(1) . "\n";
        co::sleep(1);
    }
});
