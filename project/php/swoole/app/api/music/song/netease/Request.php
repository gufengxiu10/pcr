<?php

declare(strict_types=1);

namespace app\api\music\song\netease;

use app\api\music\Cache;
use app\api\music\facade\Cache as FacadeCache;
use GuzzleHttp\Client;
use Swlib\Saber;
use Swoole\Coroutine;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Request
{

    private array $header = [];
    private string|null|array $proxy = '';

    const BASE_URL = 'http://music.163.com/weapi';

    public function send(string $url, $method = 'GET', array $option = [])
    {
        if (str_contains($url, '{id}')) {
            $info = FacadeCache::get('info');
            if (!$info) {
                throw new Excption('未登录');
            }
            $url = str_replace('{id}', (string)$info['account']['id'], $url);
        }

        if (array_key_exists('uid', $option['data'])) {
            $info = FacadeCache::get('info');
            if (!$info) {
                throw new Excption('未登录');
            }
            $option['data']['uid'] = (string)$info['account']['id'];
        }


        $cacheName = implode('_', array_filter(explode('/', $url)));
        if (!FacadeCache::has($cacheName)) {
            $optionInit = [];
            $optionInit['headers'] = $this->getHeader();
            if ($this->proxy) {
                $optionInit['proxy'] = $this->proxy;
            }

            $client = Saber::create($optionInit);

            $cookies = $this->getCookie(false);
            if (isset($cookies['__csrf'])) {
                $option['data']['__csrf'] = $cookies['__csrf'];
            }

            if (!empty($option['data'])) {
                $option['data'] = Encrypt::init()->aescbc($option['data']);
            }

            $res = $client->request([
                'uri' => self::BASE_URL . str_replace(self::BASE_URL, '', $url),
                'method' => $method,
                'data' => $option['data'] ?? []
            ]);


            if (isset($option['cache']) && $option['cache'] === false) {
                return new Response($res);
            }

            FacadeCache::set($cacheName, new Response($res));
        };

        return FacadeCache::get($cacheName);
    }

    private function check()
    {
        // Coroutine
        if (extension_loaded('swoole') && Coroutine::getCid() > 0) {
            return 1;
        }

        return 0;
    }

    public static function init()
    {
        return new static;
    }

    private function getHeader()
    {
        if (!$this->header) {


            $this->header = [
                'Referer'         => 'https://music.163.com/',
                'Cookie'          => $this->getCookie(),
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

    private function getCookie(bool $string = true)
    {
        $cookies = [
            'appver' => '1.5.9',
            'os'    => 'pc',
        ];

        if (FacadeCache::get('info')) {
            $cookies = FacadeCache::get('cookies');
        }

        if ($string === true) {
            $str = [];
            foreach ($cookies as $key => $val) {
                $str[] = $key . '=' . $val;
            }
            return implode('; ', $str);
        }

        return $cookies;
    }

    public function setProxy($value)
    {
        $this->proxy = $value;
        return $this;
    }
}
