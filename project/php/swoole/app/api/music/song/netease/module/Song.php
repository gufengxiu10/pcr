<?php

declare(strict_types=1);

namespace app\api\music\song\netease\module;

use app\api\music\song\netease\Request;
use Swlib\SaberGM;

class Song
{

    const SEARCH_URL = '/cloudsearch/pc';
    const SONG_URL = '/v3/song/detail/';
    const ALBUM_URL = '/v1/album/';
    const SONG_BASE_URL = '/song/enhance/player/url';

    /**
     * @name: 音乐搜索
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 09:57:14
     * @return {*}
     */
    public function search(string $searchWord, bool $bool = false)
    {
        $res = Request::init()
            ->send(self::SEARCH_URL, 'POST', [
                'data'  => [
                    's'      => $searchWord,
                    'type'   =>  1,
                    'limit'  =>  30,
                    'total'  => 'true',
                    'offset' =>  0,
                ]
            ]);

        return $res->toArray($bool);
    }

    /**
     * @name: 根据音乐ID搜索
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 09:57:14
     * @return {*}
     */
    public function song(string|int $id)
    {
        $res = Request::init()
            ->setProxy('http://192.168.1.8:8866')
            ->send(self::SONG_URL, 'POST', [
                'data'  => [
                    'c' => '[{"id":' . $id . ',"v":0}]',
                ]
            ]);

        return $res->toArray();
    }

    /**
     * @name: 获得歌的路径
     * @param {*} string
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 16:04:50
     * @return {*}
     */
    public function url(string|int $id)
    {
        $res = Request::init()
            ->send(self::SONG_BASE_URL, 'POST', [
                'data'  => [
                    'ids' => [$id],
                    'br'  => 320 * 1000,
                ]
            ]);

        return $res->url(true);
    }

    /**
     * @name: 歌曲下载
     * @param {*} $fileName
     * @param {*} $url
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 16:11:40
     * @return {*}
     */
    public function download($fileName, string $url)
    {
        $req = SaberGM::get($url);
        // $client = new Client();
        // $req = $client->request('GET', $url);
        $body = $req->getBody();
        dump($body);
        $fileName = $fileName . '.' . substr($url, strrpos($url, '.') + 1);
        $fd = fopen('./music/' . $fileName, 'w+');
        while (!$body->eof()) {
            $data = $body->read(200 * 1024);
            fwrite($fd, $data);
        }
    }

    /**
     * @name: 检测歌曲状态
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-23 15:09:44
     * @return {*}
     */
    public function check(string $id)
    {
        $this->song($id);
    }

    public function setDownLoad(bool $bool): static
    {
        $this->download = $bool;
        return $this;
    }
}
