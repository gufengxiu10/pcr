<?php

declare(strict_types=1);

namespace app\api\music\song\netease\module;

use app\api\music\Cache;
use app\api\music\song\netease\Request;


class User
{
    // 手机登录
    const INFO_URL = '/v1/user/detail/';

    /**
     * @name: 用户详情
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-22 10:21:25
     * @return {*}
     */
    public function info()
    {
        $info = Cache::init()->get('netease.info');
        $res = Request::init()
            ->setProxy('http://192.168.1.8:8866')
            ->send(self::INFO_URL . $info['account']['id'], 'POST', [
                'data'  => []
            ]);

        dump($res);
        return (string)$res->getBody();
    }
}
