<?php

declare(strict_types=1);

namespace app\api\music\son;

use app\api\music\contract\Api;
use app\api\music\encrypt\Netease as EncryptNetease;
use app\api\music\request\Netease as RequestNetease;
use GuzzleHttp\Client;
use Swlib\Saber;
use Swlib\SaberGM;
use Swoole\Coroutine;

class Netease implements Api
{

    const SEARCH_URL = 'http://music.163.com/weapi/cloudsearch/pc';
    const SONG_URL = 'http://music.163.com/weapi/v3/song/detail/';
    const ALBUM_URL = 'https://music.163.com/weapi/v1/album/';
    const SONG_BASE_URL = 'http://music.163.com/weapi/song/enhance/player/url';

    private array $header = [];

    /**
     * @name: 音乐搜索
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 09:57:14
     * @return {*}
     */
    public function search(string $searchWord)
    {
        $body = [
            's'      => $searchWord,
            'type'   =>  1,
            'limit'  =>  30,
            'total'  => 'true',
            'offset' =>  0,
        ];

        $body = EncryptNetease::init()->aescbc($body);
        $data = $this->curl(self::SEARCH_URL, $body, 'POST');
        return $this->parseAll($data);
    }

    /**
     * @name: 音乐搜索
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 09:57:14
     * @return {*}
     */
    public function searchOnce(string $searchWord)
    {
        $res = RequestNetease::init()->send(self::SEARCH_URL, 'POST', [
            'data' => [
                's'      => $searchWord,
                'type'   =>  1,
                'limit'  =>  30,
                'total'  => 'true',
                'offset' =>  0,
            ]
        ]);

        return $this->parseOnce($res->getBody());
    }

    /**
     * @name: 根据音乐ID搜索
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 09:57:14
     * @return {*}
     */
    public function song(string $id)
    {
        $body = [
            'c' => '[{"id":' . $id . ',"v":0}]',
        ];

        $body = EncryptNetease::init()->aescbc($body);
        $data = $this->curl(self::SONG_URL, $body, 'POST');
        return $this->parseOnce($data);
    }

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
        $body = [
            'total'         => 'true',
            'offset'        => '0',
            'id'            => $id,
            'limit'         => '1000',
            'ext'           => 'true',
            'private_cloud' => 'true',
        ];

        $body = EncryptNetease::init()->aescbc($body);
        $data = $this->curl(self::ALBUM_URL . $id, $body, 'POST');
        return $this->parseAll($data);
    }

    public function setDownLoad(bool $bool): static
    {
        $this->download = $bool;
        return $this;
    }

    /**
     * @name: 获得歌的路径
     * @param {*} string
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 16:04:50
     * @return {*}
     */
    public function url(string $id)
    {
        $body = [
            'ids' => array($id),
            'br'  => 320 * 1000,
        ];

        $body = EncryptNetease::init()->aescbc($body);
        $data = $this->curl(self::SONG_BASE_URL, $body, 'POST');
        $data = json_decode($data, true);
        if ($this->download === true) {
            $info = $this->song($id);
            $this->download($info['name'], $data['data'][0]['url']);
        }
        return $data;
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
    public function download($fileName, $url)
    {
        $client = new Client();
        $req = $client->request('GET', $url);
        $body = $req->getBody();
        $fileName = $fileName . '.' . substr($url, strrpos($url, '.') + 1);
        $fd = fopen('./music/' . $fileName, 'w+');
        while (!$body->eof()) {
            $data = $body->read(200 * 1024);
            fwrite($fd, $data);
        }
    }


    public function curl($url, $body, $method = 'GET')
    {
        if (extension_loaded('swoole') && Coroutine::getCid() > 0) {
            $client = Saber::create([
                'header' => $this->getHeader(),
                'proxy' => 'http://192.168.1.8:8866'
            ]);

            $res = $client->request([
                'uri' => $url,
                'method' => $method,
                'data'  => $body
            ]);

            dump($res);
            return (string)$res->getBody();
        } else {
            $header = array_map(function ($k, $v) {
                return $k . ': ' . $v;
            }, array_keys($this->getHeader()), $this->getHeader());
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($body) ? http_build_query($body) : $body);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 20);
            curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
            curl_setopt($curl, CURLOPT_IPRESOLVE, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            for ($i = 0; $i < 3; $i++) {
                $raw = curl_exec($curl);
                $this->info = curl_getinfo($curl);
                $this->error = curl_errno($curl);
                $this->status = $this->error ? curl_error($curl) : '';
                if (!$this->error) {
                    break;
                }
            }

            curl_close($curl);
            return $raw;
        }
    }

    /**
     * @name: 获得头信息
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-20 10:07:59
     * @return {*}
     */
    public function getHeader(): array
    {
        if (!$this->header) {
            $this->header = [
                'Referer'         => 'https://music.163.com/',
                'Cookie'          => 'appver=1.5.9; os=pc; __remember_me=true; osver=%E7%89%88%E6%9C%AC%2010.13.5%EF%BC%88%E7%89%88%E5%8F%B7%2017F77%EF%BC%89;',
                'User-Agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/605.1.15 (KHTML, like Gecko)',
                'X-Real-IP'       => long2ip(mt_rand(1884815360, 1884890111)),
                'Accept'          => '*/*',
                'Accept-Language' => 'zh-CN,zh;q=0.8,gl;q=0.6,zh-TW;q=0.4',
                'Connection'      => 'keep-alive',
                'Content-Type'    => 'application/x-www-form-urlencoded'
            ];
        }

        return $this->header;
    }

    public function parse($songs)
    {
        $format = [];
        foreach ($songs as $val) {
            $format[] = $this->format($val);
        }

        return $format;
    }

    public function parseAll($data): array
    {
        $format = [];
        $data = json_decode($data, true);
        if (isset($data['result'])) {
            $format = $this->parse($data['result']['songs']);
        }

        if (isset($data['songs'])) {
            $format = $this->parse($data['songs']);
        }

        return $format;
    }

    public function parseOnce($data): array
    {
        $data = json_decode($data, true);
        if (isset($data['result'])) {
            $format = $this->parse($data['result']['songs']);
        }

        if (isset($data['songs'])) {
            $format = $this->parse($data['songs']);
        }

        return $format[0];
    }

    public function format($data)
    {
        $format = [
            'name'      => $data['name'],
            'id'        => $data['id'],
            'pic_url'   => $data['al']['picUrl'] ?? '',
            'alias'     => $data['alia'],
            'author'    => $data['ar']  ?? '',
            // 'is_vip'    => $data['fee'] == 1 ? 1 : 0,
        ];

        return $format;
    }
}
