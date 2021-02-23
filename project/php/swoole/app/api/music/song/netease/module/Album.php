<?php

declare(strict_types=1);

namespace app\api\music\song\netease\module;

use app\api\music\song\netease\Request;

class Album
{

    const SEARCH_URL = '/cloudsearch/pc';
    const SONG_URL = '/v3/song/detail/';
    const ALBUM_URL = '/v1/album/';
    const SONG_BASE_URL = '/song/enhance/player/url';

    /**
     * @name: 获得专辑
     * @param {*} string
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 14:43:24
     * @return {*}
     */
    public function album(string $id)
    {
        $res = Request::init()
            ->setProxy('http://192.168.1.8:8866')
            ->send(self::ALBUM_URL, 'POST', [
                'data'  => [
                    'total'         => 'true',
                    'offset'        => '0',
                    'id'            => $id,
                    'limit'         => '1000',
                    'ext'           => 'true',
                    'private_cloud' => 'true',
                ]
            ]);

        return (string)$res->getBody();
    }
}
