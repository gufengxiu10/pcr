<?php

declare(strict_types=1);
require_once "vendor/autoload.php";

date_default_timezone_set("Asia/Shanghai");

use GuzzleHttp\Client;
use Predis\Client as PredisClient;
use Swlib\SaberGM;

Co\run(function () {
    go(function () {
        $redis = new PredisClient([
            'host' => '172.200.1.7',
            'prot'  => 6379
        ]);

        $redis->auth('gufengxiu10');
        $date = '2021-01-23';
        if (!$redis->exists('pxixv-' . $date)) {
            $d = [];
            for ($i = 1; $i < 4; $i++) {
                $res = SaberGM::get('https://pixiviz.pwp.app/api/v1/illust/rank?mode=week_original&date=' . $date . '&page=' . $i, [
                    'heards' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) 
                        AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 
                        Safari/537.36 Edg/88.0.705.50'
                    ],
                    'timeout' => 100,
                    'retry_time' => 2
                ]);

                $data = json_decode($res->getBody()->getContents(), true);
                $redis->set('pxixv-all-' . $date, json_encode($data));
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

        for ($i = 0; $i < 4; $i++) {
            go(function () use ($i, &$data, $date) {
                while (1) {
                    if (!$url = array_shift($data)) {
                        break;
                    }

                    try {
                        $res = SaberGM::get(str_replace(
                            'https://i.pximg.net',
                            'https://pixiv-image-jp.pwp.link',
                            $url
                        ), [
                            'headers' => [
                                'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0',
                                ':authority' => 'pixiv-image-jp.pwp.link',
                                ':method' => 'GET',
                                ':path' => str_replace('https://i.pximg.net', '', $url),
                                ':scheme' => 'https',
                                'origin' => 'https://pixiviz.pwp.app',
                                'referer' => 'https://pixiviz.pwp.app/',
                            ],
                            'timeout' => 20,
                            'retry_time' => 3
                        ]);

                        if ($res->getStatusCode() != 200) {
                            dump($res->getStatusCode());
                            continue;
                        }
                        $body = $res->getBody();
                        $fileName = substr($url, strrpos($url, '/') + 1);
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
                        dump('成功：' . $i . '-' . $fileName);
                        dump($data);
                        co:
                        sleep(1);
                    } catch (\Throwable $th) {
                        continue;
                    }
                }
            });
        }
    });
});
