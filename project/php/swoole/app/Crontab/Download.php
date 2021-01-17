<?php

declare(strict_types=1);

namespace App\Crontab;

use GuzzleHttp\Client;

class Download
{
    public function download()
    {
        $client = new Client();
        $date = date('Y-m-d', strtotime("-10 day"));
        // dd(date('Y-m-d',strtotime("-1 day")));
        $body = $client->request('GET', 'http://172.200.1.5/api/biu/get/rank', [
            'query' => [
                // ?mode=day&totalPage=5&groupIndex=0
                'mode' => 'day',
                'totalPage' => 5,
                'date' => $date
            ]
        ])->getBody();


        $data = json_decode($body->getContents(), true);
        foreach ($data['msg']['rst']['data'] as $key => $val) {
            dump($key);
            $body = $client->request('GET', 'http://172.200.1.5:4001/api/biu/do/dl', [
                'query' => [
                    "kt" => $date,
                    "workId" => $val['all']['id'],
                    "data" => json_encode($val['all'], JSON_UNESCAPED_UNICODE)
                ]
            ])->getBody();
        }
    }


    public function check()
    {
        # code...
    }
}
