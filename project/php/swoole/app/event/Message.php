<?php

namespace app\event;

use Anng\lib\App;
use app\api\music\song\netease\Netease;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class Message
{
    private $ws;
    private $frame;
    private $data = [];
    private $app;

    public function __construct($ws, $frame)
    {
        $this->run($ws, $frame);
    }

    public function run($ws, $frame)
    {
        $data = json_decode($frame->data, true);
        if (isset($data['message_type']) && $data['message_type'] == 'group') {
            if (strpos($data['message'], '点歌') !== false) {
                $netease = new Netease;
                dump(str_replace('点歌 ', '', $data['message']));
                $info = $netease->module('song')->search(str_replace('点歌 ', '', $data['message']), true);
                if ($info['fee'] != 1) {
                    $pushData = [
                        "type" => "163",
                        "id" => $info['id']
                    ];
                } else {
                    $url = $netease->module('song')->url($info['id']);
                    $pushData = [
                        "type" => "custom",
                        "url" => $url['url'],
                        "audio" => $url['url'],
                        "title" => $info['name'],
                        'content' => $info['author'][0]['name'] ?? '未知歌手',
                        'image' => $info['pic_url']
                    ];
                }

                dump($pushData);
                $ws->push(json_encode([
                    "action"        => "send_group_msg",
                    "params" => [
                        "group_id" => $data['group_id'],
                        "message" => [
                            "type" => "music",
                            "data" => $pushData
                        ]
                    ],
                ], JSON_UNESCAPED_UNICODE));
            }
        }
    }

    /**
     * @name: 检测来自Cq连接并记录下pd
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-08 14:34:44
     * @return {*}
     */
    public function checkCq()
    {
        if (
            is_array($this->data)
            && array_key_exists('post_type', $this->data)
            && array_key_exists('meta_event_type', $this->data)
            && $this->data['post_type'] = 'meta_event'
            && $this->data['meta_event_type'] = 'lifecycle'
        ) {
            $this->app->redis->set('cqFd', $this->frame->fd);
        }
    }

    public function control()
    {
        $finder = new Finder();
        $data = [];
        foreach ($finder->create()->in(dirname(dirname(__DIR__)) . '/keys') as $file) {
            $d = include $file->getRealPath();
            $data = array_merge($data, $d);
        }
        if (is_array($this->data) && array_key_exists('post_type', $this->data) && $this->data['post_type'] == 'message') {
            if (!empty($data) && $this->data['message_type'] == 'group') {
                $current = [];
                foreach ($data[$this->data['message_type']] as $value) {
                    if (!in_array($this->data['message'], $value['key'])) {
                        continue;
                    }
                    $current = $value;
                }

                if (!empty($current)) {
                    $controller = $current['class'];
                    $method = $current['method'];
                    $ref = new ReflectionClass($controller);
                    $object = $ref->getConstructor() === null ? $object = $ref->newInstanceArgs() : $object = $ref->newInstanceArgs([$this->data, $this->ws, $this->frame, $this->app]);
                    $object->$method();
                }
            }
        }
    }
}
