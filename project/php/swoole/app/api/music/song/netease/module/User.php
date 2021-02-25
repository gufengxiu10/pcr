<?php

declare(strict_types=1);

namespace app\api\music\song\netease\module;

use app\api\music\Cache;
use app\api\music\song\netease\Request;


class User
{
    //用户详情
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

        return $res;
    }

    /**
     * @name: 用户电台
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-25 17:40:24
     * @return {*}
     */
    public function dj()
    {
        # code...
    }

    /**
     * @name: 用户动态
     * @param {*} Type
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-25 17:40:44
     * @return {*}
     */
    public function dynamic()
    {
        # code...
    }

    /**
     * @name: 用户粉丝
     * @param {*} Type
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-25 17:41:24
     * @return {*}
     */
    public function fans()
    {
        # code...
    }

    /**
     * @name: 用户关注
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-25 17:42:19
     * @return {*}
     */
    public function follow()
    {
        # code...
    }

    /**
     * @name: 用户歌单
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-25 17:43:39
     * @return {*}
     */
    public function songSheet()
    {
        # code...
    }

    /**
     * @name: 用户排行
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-25 17:44:26
     * @return {*}
     */
    public function record()
    {
        # code...
    }

    /**
     * @name: 收藏计数
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-25 17:45:33
     * @return {*}
     */
    public function subcount()
    {
        # code...
    }

    /**
     * @name: 编辑用户信息
     * @param {*} Type
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-25 17:46:04
     * @return {*}
     */
    public function update()
    {
        # code...
    }
}
