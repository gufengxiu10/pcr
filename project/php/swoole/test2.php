<?php

use Anng\Plug\Oos\Aliyun\Objects;
use Anng\Plug\Oos\Auth;
use GuzzleHttp\Client;
use OSS\Model\BucketInfo;
use Predis\Client as PredisClient;
use Swoole\Process;
use Swoole\Coroutine;
use function Swoole\Coroutine\batch;

require_once "vendor/autoload.php";

date_default_timezone_set("Asia/Shanghai");

Coroutine::set(['hook_flags' => SWOOLE_HOOK_ALL]);

$start_time = microtime(true);
Coroutine\run(function () {
    $use = microtime(true);
    $redis = new PredisClient([
        'host' => '172.200.1.7',
        'prot'  => 6379
    ]);

    $redis->auth('gufengxiu10');

    $date = '2021-01-06';
    $cliden = new Client;
    if (!$redis->exists('pxixv-' . $date)) {
        $d = [];
        for ($i = 1; $i < 4; $i++) {
            $res = $cliden->request('get', 'https://pixiviz.pwp.app/api/v1/illust/rank?mode=day&date=' . $date . '&page=' . $i, [
                'heards' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36 Edg/88.0.705.50'
                ]
            ]);

            $data = json_decode($res->getBody()->getContents(), true);
            $data = $data['illusts'];
            foreach ($data as $val) {
                if (!empty($val['meta_single_page'])) {
                    array_push($d, $val['meta_single_page']['original_image_url']);
                }
            }
        }

        $redis->setex('pxixv-' . $date, 3600, json_encode($d));
    }

    $data = $redis->get('pxixv-' . $date);
    $data = json_decode($data, true);
    $let = (int)ceil(dump(count($data) / 4));
    $fd = $ki = array_chunk($data, $let);
    dump($fd);
    $co = [];
    foreach ($fd as $key => $val) {
        array_push($co, function () use ($key, $val, $ki, $cliden, $date) {
            foreach ($val as $k => $v) {
                $res = $cliden->request('GET', str_replace('https://i.pximg.net', 'https://pixiv-image-jp.pwp.link', $v), [
                    'headers' => [
                        'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0',
                        ':authority' => 'pixiv-image-jp.pwp.link',
                        ':method' => 'GET',
                        ':path' => str_replace('https://i.pximg.net', '', $v),
                        ':scheme' => 'https',
                        'origin' => 'https://pixiviz.pwp.app',
                        'referer' => 'https://pixiviz.pwp.app/',
                    ]
                ]);

                if ($res->getStatusCode() != 200) {
                    dump($res->getStatusCode());
                    continue;
                }
                $body = $res->getBody();
                $fileName = substr($v, strrpos($v, '/') + 1);
                if (!is_dir('./im/' . $date)) {
                    mkdir('./im/' . $date, 0777, true);
                }

                $al = './im/' . $date . '/' . $fileName;
                $wd = fopen($al, 'w');

                while (!$body->eof()) {
                    $content = $body->read(1024 * 200);
                    fwrite($wd, $content);
                }

                fclose($wd);
                dump('成功：' . $key . '-' . $k . '-' . $fileName);
                unset($ki[$key][$k]);
                dump($ki);
            }
        });
    }
    $use = microtime(true) - $use;
    echo "Use {$use}s, Result:\n";
});
$end_time =  microtime(true) - $start_time;
echo "Use {$end_time}s, Done\n";
