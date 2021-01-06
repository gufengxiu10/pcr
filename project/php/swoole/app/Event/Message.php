<?php

namespace App\Event;

class Message
{
    public function run($ws, $frame)
    {
        $data = json_decode($frame->data, true);
        $this->control($data);
    }


    public function control($ct)
    {
        if (!(isset($ct['message_type']) && method_exists($this, $method = $ct['message_type'] . 'Message'))) {
            return false;
        }
        $this->$method();
    }

    public function groupMessage()
    {
        echo 1;
    }
}
