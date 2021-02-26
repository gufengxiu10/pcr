<?php

declare(strict_types=1);

namespace app\api\music\song\netease\module;

use app\api\music\song\netease\Request;


class User
{
    //用户详情
    const INFO = '/v1/user/detail/{id}';
    const DJ = '/dj/program/{id}';
    const DYNAMIC = '/event/get/{id}';
    const FANS = '/user/getfolloweds/{id}';
    const FOLLOW = '/user/getfollows/{id}';
    const SONG_SHEET = '/user/playlist';
    const RECORD = '/v1/play/record';
    const SUB_COUNT = '/weapi/subcount';
    const UPDATE = '/weapi/user/profile/update';

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
        $res = Request::init()
            ->setProxy('http://192.168.1.8:8866')
            ->send(self::INFO, 'POST', [
                'data'  => [],
                'cache' => false
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
        $res = Request::init()
            ->send(self::DJ, 'POST', [
                'data'  => []
            ]);

        return $res;
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
        $res = Request::init()
            ->send(self::DYNAMIC, 'POST', [
                'data'  => []
            ]);

        return $res;
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
        $res = Request::init()
            ->send(self::FANS, 'POST', [
                'data'  => []
            ]);

        return $res;
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
        $res = Request::init()
            ->send(self::FOLLOW, 'POST', [
                'data'  => [
                    'offset' => 0,
                    'limit' =>  30,
                    'order' => 'true',
                ],
            ]);

        return $res;
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
        $res = Request::init()
            ->send(self::SONG_SHEET, 'POST', [
                'data'  => [
                    'uid' => 'id',
                    'limit' => 30,
                    'offset' =>  0,
                    'includeVideo' => true,
                ],
            ]);

        return $res;
    }

    /**
     * @name: 用户排行
     * @param string|int $uid 用户ID
     * @param int $type  1: 最近一周, 0: 所有时间
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-25 17:44:26
     * @return {*}
     */
    public function record()
    {
        $res = Request::init()
            ->send(self::RECORD, 'POST', [
                'data'  => [
                    'uid' => 'id',
                    'type' => 30,
                ]
            ]);

        return $res;
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
        $res = Request::init()
            ->send(self::SONG_SHEET, 'POST', [
                'data'  => []
            ]);

        return $res;
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
        $res = Request::init()
            ->send(self::SONG_SHEET, 'POST', [
                'data'  => [
                    'avatarImgId' => '0',
                    'birthday' => 'query.birthday',
                    'city' => ' query.city',
                    'gender' => 'query.gender',
                    'nickname' => 'query.nickname',
                    'province' => 'query.province',
                    'signature' => 'query.signature',
                ]
            ]);

        return $res;
    }
}
