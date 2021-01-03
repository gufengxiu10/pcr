<?php

require_once "vendor/autoload.php";

use Swlib\SaberGM;
//创建WebSocket Server对象，监听0.0.0.0:9502端口
$ws = new Swoole\WebSocket\Server('0.0.0.0', 9502);

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    echo '链接成功' . PHP_EOL;
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    // echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $data = json_decode($frame->data, true);

    if (isset($data['message'])) {
        if ($data['message_type'] == 'group') {
            if ($data['message'] == '来张图片') {
                $images = scandir('/images/pcr');
                if (!empty($images) && !empty($images[rand(1, count($images))])) {
                    $img = $images[rand(1, count($images) - 2)];
                    dump($images);
                    dump(count($images));
                    dump(count($images) - 2);
                    $postData = [
                        "action"        => "send_group_msg",
                        "params" => [
                            "group_id"      => $data['group_id'],
                            "message" => [
                                "type" => "image",
                                "data" => [
                                    "file" => "http://172.200.1.4:9000/pcr/" . $img,
                                ]
                            ]
                        ],
                    ];
                    $ws->push($frame->fd, json_encode($postData, JSON_UNESCAPED_UNICODE));
                } else {
                    $postData = [
                        "action"        => "send_group_msg",
                        "params" => [
                            "group_id"      => $data['group_id'],
                            "message" => '暂无图片'
                        ],
                    ];
                    $ws->push($frame->fd, json_encode($postData, JSON_UNESCAPED_UNICODE));
                }
            }
        } else if ($data['message'] == '1') {
            $postData = [
                "action" => "get_friend_list",
                "user_id"   => "296718933",
                "no_cache"  => false,
            ];
            $ws->push($frame->fd, json_encode($postData, JSON_UNESCAPED_UNICODE));
        }
    } else if (isset($data['retcode'])) {
        dump($data);
    } else {
        dump($data);
    }
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();
