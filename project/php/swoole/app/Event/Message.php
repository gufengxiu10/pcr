<?php

namespace App\Event;

use Anng\lib\App;
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
        $ws->push('测试');
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
