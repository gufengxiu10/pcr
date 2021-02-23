<?php

declare(strict_types=1);

namespace app\api\music\song\netease\module;

use app\api\music\song\netease\Request;


class User
{
    // 手机登录
    const PHONE_URL = '/login/cellphone';

    /**
     * @name: 手机登录
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-22 10:21:25
     * @return {*}
     */
    public function phone()
    {
        $res = Request::init()
            ->saveCookies(true)
            ->setProxy('http://192.168.1.8:8866')
            ->send(self::PHONE_URL, 'POST', [
                'data'  => [
                    'phone' => '13672666381',
                    'countrycode' => '86',
                    'password' => md5('gufengxiu10'),
                    'rememberLogin' => 'true',
                    'csrf_token' => ''
                ]
            ]);

        return (string)$res->getBody();
    }
}
