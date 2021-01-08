<?php

declare(strict_types=1);


namespace App\Controller\Group;

class Price
{
    public function __construct($data, $ws)
    {
        $this->ws = $ws;
    }

    public function sendImg()
    {
        $postData = [
            "action"        => "send_group_msg",
            "params" => [
                "group_id" => "415446505",
                "message" => [
                    "type" => "image",
                    "data" => [
                        "file" => "http://172.200.1.4:9000/pcr/49260/[work] 陰陽師本格幻想RPG.jpg",
                    ]
                ]
            ],
        ];

        $this->ws->push(1, json_encode($postData, JSON_UNESCAPED_UNICODE));
    }
}
