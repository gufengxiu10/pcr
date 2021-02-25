<?php

declare(strict_types=1);

namespace app\api\music\song\netease\format;


class Login
{
    public function __construct(public mixed $data)
    {
    }

    public function info()
    {
        $loginType = array_column($this->data['bindings'], null, 'type');
        return [
            'account' => $this->data['account'],
            'token' => $this->data['token'],
            'info'  => $this->data['profile'],
            'login' => $loginType[$this->data['loginType']],
        ];
    }

    public function getData()
    {
        return $this->data;
    }
}
