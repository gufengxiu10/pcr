<?php

$path = dirname(dirname(__DIR__));
require_once $path . "/vendor/autoload.php";

use Predis\Client;
use Swlib\SaberGM;

go(function () {
    $websocket = SaberGM::websocket('ws://127.0.0.1:9502');
    $data = [
        'message_type' => 'crontablSend',
        'lists' => [
            'toDayPrice',
        ]
    ];
    $websocket->push(json_encode($data));
    $websocket->close();
});
