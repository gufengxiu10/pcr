<?php

namespace App\Event;

use Anng\lib\App;
use Predis\Client;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class Test
{
    public function __construct($frame)
    {
        $this->run($frame);
    }

    public function run($frame)
    {
        go(function () use ($frame) {
            $s = microtime(true);
            $redis = new Client([
                'host' => '172.200.1.7',
                'prot'  => 6379
            ]);

            $redis->auth('gufengxiu10');
            $date = '2021-01-13';
            $data = $redis->get('pxixv-all-' . $date);
            $data = json_decode($data, true);
            $data = $data['illusts'];
            foreach ($data as $val) {
                # 子协程START
                go(function () use ($val, $date, $frame) {
                    $user = [];
                    $info = [];
                    $imgs = [];
                    $pdo = $frame->db->getConnection();
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
                            'type' => $val['type'],1
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
                    $frame->db->pushConnection($pdo);
                    # 子协程END
                });
            }
            echo 'use ' . (microtime(true) - $s) . ' s';
        });

        // $postData = [
        //     "action"        => "send_group_msg",
        //     "params" => [
        //         "group_id"      => 415446505,
        //         "message" => [
        //             "type" => "image",
        //             "data" => [
        //                 "file" => "http://172.200.1.4:9000/pcr/default.png",
        //             ]
        //         ]
        //     ],
        // ];
        // $ws->push($frame->fd, json_encode($postData, JSON_UNESCAPED_UNICODE));
        // $this->checkCq();
        // $this->ws = $ws;
        // $this->frame = $frame;
        // $this->data = json_decode($frame->data, true);
        // $this->control();
    }
}
