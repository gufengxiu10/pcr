<?php

declare(strict_types=1);

namespace App\Crontab;

use Anng\lib\facade\App;
use Anng\lib\facade\Connect;
use Anng\lib\facade\Redis;
use GuzzleHttp\Client;
use Swlib\SaberGM;
use Swoole\Coroutine\System;

class ToDayPrice
{
    public function run()
    {
        if (!Redis::exists('lolicon')) {
            $res = SaberGM::get('https://api.lolicon.app/setu/' . '?' . http_build_query([
                'apikey' => '32906725601ba5cf18c942',
                'r18' => 0,
                'num' => 10
            ]));

            $data = json_decode($res->getBody()->getContents(), true);
            Redis::set('lolicon', json_encode($data, JSON_UNESCAPED_UNICODE));
        }

        $data = Redis::get('lolicon');
        $data = json_decode($data, true);
        $fds = Connect::get();
        foreach ($fds as $key => $value) {
            foreach ($data['data'] as $val) {
                $res = SaberGM::get($val['url'], [
                    'timeout' => 20,
                    'retry_time' => 3
                ]);

                if ($res->getStatusCode() != 200) {
                    dump($res->getStatusCode());
                    continue;
                }

                $fileName = '/images/t/' . substr($val['url'], strrpos($val['url'], '/') + 1);
                $wd = fopen($fileName, 'w');

                $body = $res->getBody();
                if (!$body->eof()) {
                    $content = $body->read(1024 * 200);
                    fwrite($wd, $content);
                }

                fclose($wd);

                $postData = [
                    "action" => "send_group_msg",
                    "params" => [
                        "group_id" => 415446505,
                        "message" => [
                            "type" => "image",
                            "data" => [
                                "file" => 'file://' . $fileName,
                            ]
                        ]
                    ],
                ];
                dump($postData);
                $value['ws']->push(json_encode($postData, JSON_UNESCAPED_UNICODE));
                break;
            }
        }

        // $this->ws->push(1, json_encode($postData, JSON_UNESCAPED_UNICODE));
    }
}
