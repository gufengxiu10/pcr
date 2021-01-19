<?php

declare(strict_types=1);

namespace App\Crontab;

use GuzzleHttp\Client;

class Download
{
    public function download()
    {
        $date = date('Y-m-d', strtotime("-1 day"));
        $cli = new \Swoole\Coroutine\Http\Client('172.200.1.5', 80);
        $cli->setMethod('get');
        $status = $cli->execute('/api/biu/get/rank?' . http_build_query([
            'mode' => 'day',
            'totalPage' => 5,
            // 'date'  => $date
        ]));
        if ($status == true) {
            $data = json_decode($cli->getBody(), true);
            dump($data);
            foreach ($data['msg']['rst']['data'] as $key => $val) {
                $cli->setMethod('get');
                $status = $cli->execute('/api/biu/do/dl?' . http_build_query([
                    "kt" => $date,
                    "workId" => $val['all']['id'],
                    "data" => json_encode($val['all'], JSON_UNESCAPED_UNICODE)
                ]));
                dump($key . '-' . $status);
            }
        } else {
            dump($cli);
        }
    }


    public function check()
    {
        $cli = new \Swoole\Coroutine\Http\Client('172.200.1.5', 80);
        $cli->setMethod('GET');
        $cli->execute('/api/biu/get/status?type=download&key=__all__');
        // dump(json_decode($cli->getBody(), true));
    }
}
