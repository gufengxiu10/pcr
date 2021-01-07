<?php

namespace App\Event;

class Message
{
    private $ws;
    private $frame;
    public function run($ws, $frame)
    {
        dump($this->frame->fd);
        $this->ws = $ws;
        $this->frame = $frame;
        $data = json_decode($frame->data, true);
        $this->control($data);
    }


    public function control($ct)
    {
        if (!(isset($ct['message_type']) && method_exists($this, $method = $ct['message_type'] . 'Message'))) {
            return false;
        }
        $this->$method($ct);
    }

    public function groupMessage()
    {
    }

    public function crontablSendMessage($data)
    {

        foreach ($data['lists'] as $value) {
            if ($value == 'toDayPrice') {
                $postData = [
                    "action"        => "send_group_msg",
                    "params" => [
                        "group_id" => "93958924",
                        "message" => [
                            "type" => "image",
                            "data" => [
                                "file" => "http://172.200.1.4:9000/pcr/17810/謹賀新年.jpg",
                            ]
                        ]
                    ],
                ];

                $this->ws->push(1, json_encode($postData, JSON_UNESCAPED_UNICODE));
            }
        }
    }
}
