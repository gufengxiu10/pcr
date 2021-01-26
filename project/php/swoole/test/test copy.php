<?php

use GuzzleHttp\Client;
use Vectorface\Whip\Whip;

require_once "vendor/autoload.php";
date_default_timezone_set("Asia/Shanghai");




go(function () {
    $cli = new \Swoole\Coroutine\Http\Client('172.200.1.5', 80);
    $cli->setMethod('get');
    $cli->setData([
        'mode' => 'day',
        'totalPage' => 5,
    ]);
    $status = $cli->execute('/api/biu/get/rank');
    dump(json_decode($cli->getBody(), true));
});

dd(1);
$client = new Client();

while (1) {

    $body = $client->request('GET', 'http://172.200.1.5/api/biu/get/status', [
        'query' => [
            // ?mode=day&totalPage=5&groupIndex=0
            //ype=download&key=__all__
            'type'  => 'download',
            'key'   => '__all__'
        ]
    ])->getBody();

    dump(json_encode($body->getContents(), true));
    sleep(3);
}

dd(1);
// dd(date('Y-m-d',strtotime("-1 day")));
$body = $client->request('GET', 'http://172.200.1.5/api/biu/get/rank', [
    'query' => [
        // ?mode=day&totalPage=5&groupIndex=0
        'mode' => 'day',
        'totalPage' => 5,
        'date' => date('Y-m-d', strtotime("-3 day"))
    ]
])->getBody();
// $data = json_decode($body->getContents(), true)['msg']['rst']['data'][0];
$data = json_decode($body->getContents(), true)['msg']['rst']['data'];

foreach ($data as $value) {
    go(function () use ($client, $value) {
        $client->request('GET', 'http://172.200.1.5/api/biu/do/dl', [
            'query' => [
                "kt" => date('Ymd'),
                "workId" => $value['all']['id'],
                "data" => json_encode($value['all'], JSON_UNESCAPED_UNICODE)
            ]
        ])->getBody();
    });
}


dd(1);
dd(json_decode($body->getContents(), true));
$ip_long = [
    ['607649792', '608174079'], //36.56.0.0-36.63.255.255
    ['975044608', '977272831'], //58.30.0.0-58.63.255.255
    ['999751680', '999784447'], //59.151.0.0-59.151.127.255
    ['1019346944', '1019478015'], //60.194.0.0-60.195.255.255
    ['1038614528', '1039007743'], //61.232.0.0-61.237.255.255
    ['1783627776', '1784676351'], //106.80.0.0-106.95.255.255
    ['1947009024', '1947074559'], //116.13.0.0-116.13.255.255
    ['1987051520', '1988034559'], //118.112.0.0-118.126.255.255
    ['2035023872', '2035154943'], //121.76.0.0-121.77.255.255
    ['2078801920', '2079064063'], //123.232.0.0-123.235.255.255
    ['-1950089216', '-1948778497'], //139.196.0.0-139.215.255.255
    ['-1425539072', '-1425014785'], //171.8.0.0-171.15.255.255
    ['-1236271104', '-1235419137'], //182.80.0.0-182.92.255.255
    ['-770113536', '-768606209'], //210.25.0.0-210.47.255.255
    ['-569376768', '-564133889'], //222.16.0.0-222.95.255.255
];
$rand_key = mt_rand(0, 14);
$huoduan_ip = long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));

$token = "eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJBTk5HIiwidXVpZCI6Ijk0YzI4MWRmMzA0YTRlNjg4ZmQ5NmYyZmFjNjlkNDJjIiwiaWF0IjoxNjEwNzI4MjAwLCJhY2NvdW50Ijoie1wiZW1haWxcIjpcImFubmdoYW55dXVAZ21haWwuY29tXCIsXCJnZW5kZXJcIjotMSxcImhhc1Byb25cIjowLFwiaWRcIjo1NzYsXCJwYXNzV29yZFwiOlwiY2UyODhmZGMwYmQzYzA1OWQ0NDRkYzEyMTc1MjU0NGZcIixcInN0YXR1c1wiOjAsXCJ1c2VyTmFtZVwiOlwiQU5OR1wifSIsImp0aSI6IjU3NiJ9.qWzEM3miARRccRfurOKcJPpcz4OvxpCpmJyrPNFfgv8";
$client = new Client();
// // a476dbea9df3903fc8784594ed67a2ef
$body = $client->request('GET', 'https://api.acgmx.com/illusts/ranking', [
    'headers' => [
        'tokken' => $token,
    ],
    'query' => [
        'mode' => 'day',
    ]
])->getBody();

$url = json_decode($body->getContents(), true);
dd($url);
$body = $client->request('GET', "https://api.acgmx.com/illusts/detail", [
    'query' => [
        'illustId' => $url['data'][0]['pid'],
        'reduction' => true
    ]
])->getBody();

$data = json_decode($body->getContents(), true);
$data = $data['data']['illust'];
if (!empty($data['meta_single_page'])) {
    dump($data);
    $body = $client->request('GET', "https://api.acgmx.com/illusts/urlLook", [
        'headers' => [
            'token' => $token,
        ],
        'query' => [
            'url' => $data['meta_single_page']['original_image_url'],
            'cache' => true
        ]
    ])->getBody();

    file_put_contents('1.png', $body->getContents());
} else {
    dump($data);
    foreach ($data['meta_pages'] as $key => $val) {
        $body = $client->request('GET', "https://api.acgmx.com/illusts/urlLook", [
            'headers' => [
                'token' => $token,
            ],
            'query' => [
                'url' => $val['image_urls']['original'],
                'cache' => true
            ]
        ])->getBody();

        file_put_contents($key . '.png', $body->getContents());
    }
}
// dd();
