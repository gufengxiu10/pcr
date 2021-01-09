<?php

declare(strict_types=1);


namespace App\Controller\Group;

use Symfony\Component\Finder\Finder;

class Price
{
    public function __construct($data, $ws)
    {
        $this->ws = $ws;
        $this->data = $data;
    }

    public function sendImg()
    {
        $finder = new Finder();
        $data = [];
        foreach ($finder->create()->in('/images/pcr') as $file) {
            if (filesize($file->getRealPath()) > 0) {
                array_push($data, $file->getRealPath());
            }
        }

        $key = array_rand($data, 1);

        $postData = [
            "action"        => "send_group_msg",
            "params" => [
                "group_id" => $this->data['group_id'],
                "message" => [
                    "type" => "image",
                    "data" => [
                        "file" => "http://172.200.1.4:9000" . str_replace('/images', '', $data[$key]),
                    ]
                ]
            ],
        ];
        $this->ws->push(1, json_encode($postData, JSON_UNESCAPED_UNICODE));
    }
}
