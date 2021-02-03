<?php

declare(strict_types=1);

namespace App\Crontab;

use Anng\lib\App;
use Anng\lib\Facade;
use Anng\lib\facade\Db;
use Anng\lib\facade\Redis;
use GuzzleHttp\Client;
use Swlib\SaberGM;
use Swoole\Coroutine\System;
use Swoole\Coroutine\WaitGroup;

class Download
{

    public function download()
    {
        dump('start');
        go(function () {
            $redis = Redis::client();
            $date = '2021-02-01';
            if (!$redis->exists('pxixv-' . $date)) {
                $d = [];
                for ($i = 1; $i < 4; $i++) {
                    $res = SaberGM::get('https://pixiviz.pwp.app/api/v1/illust/rank?mode=day&date=' . $date . '&page=' . $i, [
                        'heards' => [
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) 
                            AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 
                            Safari/537.36 Edg/88.0.705.50'
                        ],
                        'timeout' => 20,
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
                            System::sleep(1);
                        } catch (\Throwable $th) {
                            continue;
                        }
                    }
                });
            }
        });
    }

    public function download3()
    {
        dump(11);
    }

    public function download2()
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

    /**
     * @name: 存储图片信息
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-03 09:15:47
     * @return {*}
     */
    public function saveImgInfo()
    {
        go(function () {
            $s = microtime(true);
            $wg = new WaitGroup();
            $date = '2021-01-13';
            $data = Redis::get('pxixv-all-' . $date);
            $data = json_decode($data, true);
            $data = $data['illusts'];
            $wg->add(count($data));
            foreach ($data as $key => $val) {
                # 子协程START
                //todo::此处使用子程的话,导致数据重复添加,原因是并发查询时候,导致各种个连接查询时数据都不存在所以各种进行添加
                go(function () use ($val, $date,  $wg, $key) {
                    $pdo = Db::getConnection();
                    $user = [];
                    $info = [];
                    $imgs = [];
                    $user = [
                        'name'          => $val['user']['name'],
                        'oid'           => $val['user']['id'],
                        'account'       => $val['user']['account'],
                        'img'           => $val['user']['profile_image_urls']['medium'],
                        'create_time'   => time(),
                        'update_time'   => time(),
                    ];


                    $id = $pdo->name('author')->insertId($user);

                    $info = $pdo->name('imgs_info')->field('id')->where('oid', $val['id'])->find();
                    if (!$info) {
                        $infoData = [
                            'oid' => $val['id'],
                            'aid' => $id,
                            'title' => $val['title'],
                            'type' => $val['type'],
                            'caption' => $val['caption'],
                            'img_create_time' => strtotime($val['create_date']),
                            'origin' => json_encode($val, JSON_UNESCAPED_UNICODE),
                            'create_time' => time(),
                            'update_time' => time()
                        ];

                        $infoId = $pdo->name('imgs_info')->insertId($infoData);
                    } else {
                        $infoId = $info['id'];
                    }

                    $tagIds = [];
                    foreach ($val['tags'] as $v) {
                        $tagInfo = $pdo->name('tag')->field('id')->where('name', $v['name'])->find();
                        if (!$info) {
                            $tid = $pdo->name('tag')->insertId([
                                'name' => $v['name'],
                                'tname' => $v['translated_name'],
                                'create_time'   => time(),
                                'update_time'   => time()
                            ]);
                        } else {
                            $tid = $tagInfo['id'];
                        }
                        $tagIds[] = $tid;
                    }

                    foreach ($tagIds as $v) {
                        $info = $pdo->name('imgs_info_middle')->field('id')
                            ->where('iid', $infoId)
                            ->where('tid', $v)
                            ->find();
                        if (!$info) {
                            $tid = $pdo->name('imgs_info_middle')->insertId([
                                'iid' => $infoId,
                                'tid' => $v,
                                'create_time'   => time(),
                                'update_time'   => time()
                            ]);
                        }
                    }

                    $field = ['iid', 'url', 'specs', 'path', 'page', 'create_time', 'update_time'];
                    if (!empty($val['meta_single_page'])) {
                        $imgs[] = [
                            'iid' => $id,
                            'url' => $val['meta_single_page']['original_image_url'],
                            'specs' => 0,
                            'path'  => $date . '/' . substr($val['meta_single_page']['original_image_url'], strrpos($val['meta_single_page']['original_image_url'], '/') + 1),
                            'page' => 0,
                            'create_time' => time(),
                            'update_time' => time(),
                        ];

                        foreach ($val['image_urls'] as $k => $v) {
                            $type = 0;
                            switch ($k) {
                                case 'square_medium':
                                    $type = 1;
                                    break;
                                case 'medium':
                                    $type = 2;
                                    break;
                                case 'large':
                                    $type = 3;
                                    break;
                                default:
                                    $type = 0;
                            };
                            $imgs[] = [
                                'iid' => $id,
                                'url' => $val['meta_single_page']['original_image_url'],
                                'specs' => $type,
                                'path'  => $date . '/' . substr($v, strrpos($v, '/') + 1),
                                'page' => 0,
                                'create_time' => time(),
                                'update_time' => time(),
                            ];
                        }
                    } else {
                        foreach ($val['meta_pages'] as $k => $v) {
                            foreach ($v['image_urls'] as $kitem => $item) {
                                $type = 0;
                                switch ($kitem) {
                                    case 'square_medium':
                                        $type = 1;
                                        break;
                                    case 'medium':
                                        $type = 2;
                                        break;
                                    case 'large':
                                        $type = 3;
                                        break;
                                    default:
                                        $type = 0;
                                };
                                $imgs[] = [
                                    'iid' => $id,
                                    'url' => $item,
                                    'specs' => $type,
                                    'path'  => $date . '/' . substr($item, strrpos($item, '/') + 1),
                                    'page' => $k,
                                    'create_time' => time(),
                                    'update_time' => time(),
                                ];
                            }
                        }
                    }

                    $pdo->name('imgs')->insertAll(['field' => $field, 'data' => $imgs]);
                    # 子协程END
                    Db::pushConnection($pdo);
                    // System::sleep(1);
                    $wg->done();
                });
            }

            $wg->wait();
            echo 'use ' . (microtime(true) - $s) . ' s';
        });
    }
}
