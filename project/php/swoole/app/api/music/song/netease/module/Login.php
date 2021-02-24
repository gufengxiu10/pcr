<?php

declare(strict_types=1);

namespace app\api\music\song\netease\module;

use app\api\music\Cache;
use app\api\music\song\netease\Excption;
use app\api\music\song\netease\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

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
        if (!Cache::init()->has('netease.info')) {
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

            if (!$res->isSuccess()) {
                throw new Excption('请求失败');
            }

            $data = json_decode((string)$res->getBody(), true);
            dump((string)$res->getBody());
            if (!$data) {
                throw new Excption('请求失败');
            }


            $cookiesOrigin = $res->getCookies();
            $cookies = [];
            foreach ($cookiesOrigin as $value) {
                $cookies[$value->name] = $value->value;
            }



            Cache::init()->set('netease.cookies', $cookies);
            Cache::init()->set('netease.info', $data, [
                'expires' => 3600 * 20
            ]);
        }

        return Cache::init()->get('netease.info');
    }
}
