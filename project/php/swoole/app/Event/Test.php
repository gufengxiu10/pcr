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
            $redis = new Client([
                'host' => '172.200.1.7',
                'prot'  => 6379
            ]);

            $redis->auth('gufengxiu10');
            $date = '2021-01-13';
            $data = $redis->get('pxixv-all-' . $date);
            $data = json_decode($data, true);
            $data = $data['illusts'];
            $insert = [];
            foreach ($data as $val) {
                // go(function () use ($val, $date, $frame) {
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

                $id = $frame->db->name('author')->insertId($user);
                // $id = $this->container->db->name('pixiv_author')->insertId($user);

                $info = [
                    'oid' => $val['id'],
                    'aid' => $id,
                    'title' => $val['title'],
                    'type' => $val['type'],
                    // 'caption' => json_encode($val['caption'], JSON_UNESCAPED_UNICODE),
                    'img_create_time' => strtotime($val['create_date']),
                    // 'origin' => json_encode($val, JSON_UNESCAPED_UNICODE),
                    'create_time' => time(),
                    'update_time' => time()
                ];

                $id = $frame->db->name('imgs_info')->insertId($info);
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

                foreach ($imgs as $v) {
                    $frame->db->name('imgs')->insertId($v);
                }
            }
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
