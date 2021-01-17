<?php

declare(strict_types=1);

namespace App\Crontab;

use GuzzleHttp\Client;

class ToDayPrice
{
    public function run($ws)
    {

        $fileName =  date('YmdHis') . '.png';
        file_put_contents('/images/pcr/' .  $fileName, 'https://i.xinger.ink:4443/images.php');
        $postData = [
            "action"        => "send_group_msg",
            "params" => [
                "group_id"      => 93958924,
                "message" => [
                    "type" => "image",
                    "data" => [
                        "file" => "http://172.200.1.4:9000/pcr/" . $fileName,
                    ]
                ]
            ],
        ];

        dump($postData);
        $ws->push(1, json_encode($postData, JSON_UNESCAPED_UNICODE));
        return;
        while (1) {
            $token = "eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJBTk5HIiwidXVpZCI6Ijk0YzI4MWRmMzA0YTRlNjg4ZmQ5NmYyZmFjNjlkNDJjIiwiaWF0IjoxNjEwNzI4MjAwLCJhY2NvdW50Ijoie1wiZW1haWxcIjpcImFubmdoYW55dXVAZ21haWwuY29tXCIsXCJnZW5kZXJcIjotMSxcImhhc1Byb25cIjowLFwiaWRcIjo1NzYsXCJwYXNzV29yZFwiOlwiY2UyODhmZGMwYmQzYzA1OWQ0NDRkYzEyMTc1MjU0NGZcIixcInN0YXR1c1wiOjAsXCJ1c2VyTmFtZVwiOlwiQU5OR1wifSIsImp0aSI6IjU3NiJ9.qWzEM3miARRccRfurOKcJPpcz4OvxpCpmJyrPNFfgv8";
            $client = new Client();
            $body = $client->request('GET', 'https://api.loli.st/pixiv/random.php', [
                'query' => [
                    'r18' => false,
                    'type' => 'json',
                ]
            ])->getBody();

            $url = json_decode($body->getContents(), true);
            $body = $client->request('GET', "https://api.acgmx.com/illusts/detail", [
                'query' => [
                    'illustId' => $url['illust_id'],
                    'reduction' => true
                ]
            ])->getBody();


            $data = json_decode($body->getContents(), true);
            $data = $data['data']['illust'];
            if (!empty($data['meta_single_page'])) {
                $ext = substr($data['meta_single_page']['original_image_url'], strrpos($data['meta_single_page']['original_image_url'], '.') + 1);
                $body = $client->request('GET', "https://api.acgmx.com/illusts/urlLook", [
                    'headers' => [
                        'token' => $token,
                    ],
                    'query' => [
                        'url' => $data['meta_single_page']['original_image_url'],
                        'cache' => true
                    ]
                ])->getBody();

                $fileName =  date('YmdHis') . '.' . $ext;
                file_put_contents('/images/pcr/' .  $fileName, $body->getContents());

                $postData = [
                    "action"        => "send_group_msg",
                    "params" => [
                        "group_id"      => 93958924,
                        "message" => [
                            "type" => "image",
                            "data" => [
                                "file" => "https://i.xinger.ink:4443/images.php",
                                "cache" => 0
                            ]
                        ]
                    ],
                ];

                dump($postData);
                $ws->push(1, json_encode($postData, JSON_UNESCAPED_UNICODE));
                break;
            }
        }
    }
}
