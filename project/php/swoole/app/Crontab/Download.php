<?php

declare(strict_types=1);

namespace App\Crontab;

use GuzzleHttp\Client;

class Download
{
    public function download()
    {

        $cli = new \Swoole\Coroutine\Http\Client('172.200.1.5', 80);
        $cli->setMethod('get');
        $cli->setData([
            'mode' => 'day',
            'totalPage' => 5,
        ]);
        $status = $cli->execute('/api/biu/get/rank');
        if ($status == true) {
            $data = json_decode($cli->getBody(), true);
            foreach ($data['msg']['rst']['data'] as $key => $val) {
                $date = date('Y-m-d', strtotime("-10 day"));
                $cli->setMethod('get');
                $cli->setData([
                    "kt" => $date,
                    "workId" => $val['all']['id'],
                    "data" => json_encode($val['all'], JSON_UNESCAPED_UNICODE)
                ]);
                $status = $cli->execute('/api/biu/do/dl');
                dump($key . '-' . $status);
            }
        }
    }


    public function check()
    {
        $cli = new \Swoole\Coroutine\Http\Client('172.200.1.5', 80);
        $cli->setMethod('get');
        $cli->setData([
            'mode' => 'day',
            'totalPage' => 5,
        ]);
        $status = $cli->execute('/api/biu/get/rank');
    }
}
