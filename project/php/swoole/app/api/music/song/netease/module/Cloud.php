<?php

declare(strict_types=1);

namespace app\api\music\song\netease\module;

use app\api\music\song\netease\Request;
use Swlib\Saber\Response;

class Cloud
{
    // 手机登录
    const PHONE_URL = '/v1/cloud/get';

    /**
     * @name: 手机登录
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-22 10:21:25
     * @return {*}
     */
    public function lists()
    {
        $res = Request::init()
            ->setProxy('http://192.168.1.8:8866')
            ->send(self::PHONE_URL, 'POST', [
                'data'  => [
                    'limit' => 30,
                    'offset' => 0,
                ]
            ]);

        // Swlib\Saber\Response
        if ($res instanceof Response) {
        }
        dump((string)$res->getBody());
        return (string)$res->getBody();
    }
}
