<?php

declare(strict_types=1);

namespace app\api\music\song\netease\module;

use app\api\music\song\netease\Request;
use Swlib\Saber\Response;

class Login
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
        // dump(dirname(__DIR__, 3));
        $res = Request::init()
            ->send(self::PHONE_URL, 'POST', [
                'data'  => [
                    'phone' => '13672666381',
                    'countrycode' => '86',
                    'password' => md5('gufengxiu10'),
                    'rememberLogin' => 'true',
                    'csrf_token' => ''
                ]
            ]);


        $cookiesOrigin = $res->getCookies();
        $cookies = [];
        foreach ($cookiesOrigin as $value) {
            $cookies[$value->name] = $value->value;
        }

        $fildName = dirname(__DIR__, 3) . '/cookies/netease_cookies.txt';
        file_put_contents($fildName, json_encode($cookies, JSON_UNESCAPED_UNICODE));

        // dump((string)$res->getBody());
        // return $res;
    }
}
