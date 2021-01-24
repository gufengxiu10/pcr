<?php

use Anng\Plug\Oos\Aliyun\Objects;
use Anng\Plug\Oos\Auth;
use GuzzleHttp\Client;
use OSS\Model\BucketInfo;

require_once "vendor/autoload.php";

date_default_timezone_set("Asia/Shanghai");


// $auth = new Auth('LTAI4GKqRce9trhJ1KGFBXT9', 'yYq0hxz2mjTP1qZtIWJCr15AptSupV');
// $auth->setBucket('cic-pixiv');
// $object = new Objects($auth);
// $object->setFile('/images/pcr/20210109')->upload();

// $cliden = new Client();
// $res = $cliden->request('get', 'https://pixiviz.pwp.app/api/v1/illust/rank?mode=day&date=2021-01-22&page=1', [
//     'heards' => [
//         'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36 Edg/88.0.705.50'
//     ]
// ]);

// dd(json_decode($res->getBody()->getContents(),true));
// https://i.pximg.net/user-profile/img/2015/04/01/09/24/51/9172583_ef46e4bda34c271df88dd5f64c2fba5e_170.png
// https://i.pximg.net/img-original/img/2020/12/26/00/00/03/86539244_p0.png"
//https://i.pximg.net/img-original/img/2021/01/21/00/13/45/87188772_p0.png
//https://pixiv-image-jp.pwp.link/img-original/img/2021/01/22/00/00/03/87208288_p0.png
$jar = new \GuzzleHttp\Cookie\CookieJar;
$cliden = new Client();
$res = $cliden->request('GET', 'https://w.wallhaven.cc/full/g8/wallhaven-g8oomq.jpg', [
    // $res = $cliden->request('GET', 'https://pixiv-image-jp.pwp.link/img-original/img/2021/01/22/00/00/05/87208314_p0.png', [
    'headers' => [
        'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0',
        // ':authority' => 'pixiv-image-jp.pwp.link',
        // ':method' => 'GET',
        // ':path' => '/img-original/img/2021/01/22/00/00/05/87208314_p0.png',
        // ':scheme' => 'https',
        // 'origin' => 'https://pixiviz.pwp.app',
        // 'referer' => 'https://pixiviz.pwp.app/',
        // // 'cache-control' => 'no-cache',
        // // 'sec-fetch-mode' => 'cors',
        // // 'sec-fetch-dest' => 'no-cache',
        // // 'pragma' => 'empty',
        // 'accept' => "*/*"
    ],
    // 'verify' => true,
    // 'stream' => true,
    // 'cookies' => $jar
    // 'proxy' => 'https://104.20.0.127'
]);

$body = $res->getBody();
$wd = fopen('./2.jpg', 'w');

while (!$body->eof()) {
    dump(1);
    $content = $body->read(1024 * 200);
    fwrite($wd, $content);
}

fclose($wd);
