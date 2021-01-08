<?php

declare(strict_types=1);


namespace App\Controller\Group;

class Price
{
    public function __construct($data, $ws)
    {
        $this->ws = $ws;
        $this->data = $data;
    }

    public function sendImg()
    {
        $postData = [
            "action"        => "send_group_msg",
            "params" => [
                "group_id" => $this->data['group_id'],
                "message" => [
                    "type" => "image",
                    "data" => [
                        "file" => "http://172.200.1.4:9000/pcr/1236873/正月特番の舞台裏.jpg",
                    ]
                ]
            ],
        ];

        $this->ws->push(1, json_encode($postData, JSON_UNESCAPED_UNICODE));
    }
}
