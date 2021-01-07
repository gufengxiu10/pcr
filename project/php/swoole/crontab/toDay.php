<?php

$path = dirname(__DIR__);
require_once $path . "/vendor/autoload.php";

use Predis\Client;
use Swlib\SaberGM;

go(function () {
    $client = new Client(['host' => '172.200.1.7', 'port'   => 6379, 'parameters' => [
        'password' => "gufengxiu10",
        'database' => 10,
    ]]);

    $client->auth('gufengxiu10');
    $toDay = 'toDay' . date('Ymd');
    if (!$client->exists($toDay)) {
        $websocket = SaberGM::get('http://172.200.1.5:4001/api/biu/get/rank?mode=day', [
            "timeout" => '30'
        ])->getBody();
        $client->setex($toDay, 3600 * 24 * 10, json_encode(json_decode($websocket, true)));
    }

    $data = json_decode($client->get($toDay), true);
    foreach ($data['msg']['rst']['data'] as $key => $value) {
        $value = $value['all'];
        if (count($value['meta_pages']) == 0) {
            sleep(2);
            $res = SaberGM::get('http://172.200.1.5:4001/api/biu/do/dl', [
                "uri_query" => [
                    'kt'    => '日榜',
                    'workID' => $value['id'],
                    'data' => json_encode($value)
                ]
            ])->getBody();
            dump(json_decode($res, true)['msg']['way']);
        }
    }
});
