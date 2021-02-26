<?php

declare(strict_types=1);

namespace app\api\music\song\netease\module;

use app\api\music\song\netease\Request;

class Album
{

    const ALBUM = '/v1/album/';
    const DETAIL = '/vipmall/albumproduct/detail';
    const DYNAMIC = '/album/detail/dynamic';
    const LIST = '/vipmall/albumproduct/list';
    // https://music.163.com/weapi/v1/album/${query.id}

    /**
     * @name: 获得专辑
     * @param {*} string
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 14:43:24
     * @return {*}
     */
    public function album($id)
    {
        $res = Request::init()
            ->send(self::ALBUM . $id, 'POST', [
                'data'  => []
            ]);

        return $res;
    }

    public function detail()
    {
        $res = Request::init()
            ->setProxy('http://192.168.1.8:8866')
            ->send(self::DETAIL, 'POST', [
                'data'  => [
                    'id' => 79921979
                ]
            ]);

        return $res;
    }

    public function dynamic()
    {
        $res = Request::init()
            ->setProxy('http://192.168.1.8:8866')
            ->send(self::DYNAMIC, 'POST', [
                'data'  => [
                    'id' => 79921979
                ]
            ]);

        return $res;
    }

    public function list()
    {
        $res = Request::init()
            ->setProxy('http://192.168.1.8:8866')
            ->send(self::LIST, 'POST', [
                'data'  => [
                    'limit' => 30,
                    'offset' =>  0,
                    'total' => true,
                    'area' =>  'ALL', //ALL:全部,ZH:华语,EA:欧美,KR:韩国,JP:日本
                    'type' => 1,
                ]
            ]);

        return $res;
    }
}
