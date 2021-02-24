<?php

declare(strict_types=1);

namespace app\api\music\song\netease;

use app\api\music\Cache;
use GuzzleHttp\Client;
use Swlib\Saber;
use Swoole\Coroutine;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Request
{

    private array $header = [];
    private bool $cookies = false;
    private string|null|array $proxy = '';

    const BASE_URL = 'http://music.163.com/weapi';

    public function send(string $url, $method = 'GET', array $option = [])
    {
        if ($this->check()) {
            if (class_exists(Saber::class)) {
                $option = [];
                $option['headers'] = $this->getHeader();
                if ($this->proxy) {
                    $option['proxy'] = $this->proxy;
                }

                $client = Saber::create($option);
                if (isset($option['data'])) {
                    $option['data'] = Encrypt::init()->aescbc($option['data']);
                }

                $res = $client->request([
                    'uri' => self::BASE_URL . str_replace(self::BASE_URL, '', $url),
                    'method' => $method,
                    'data' => $option['data'] ?? []
                ]);

                return new Response($res);
            }
        }

        if (class_exists(Client::class)) {
        }
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
            $cache = new FilesystemAdapter();
            $cacheCookies = $cache->getItem('netease.cookies');
            $cookies = 'appver=1.5.9; os=osx; __remember_me=true; osver=%E7%89%88%E6%9C%AC%2010.13.5%EF%BC%88%E7%89%88%E5%8F%B7%2017F77%EF%BC%89;';
            if (Cache::init()->has('netease.cookies')) {
                $cookiesData = Cache::init()->get('netease.cookies');
                if (!empty($cookiesData['__csrf'])) {
                    $str = [];
                    foreach ($cookiesData as $key => $val) {
                        $str[] = $key . '=' . $val;
                    }
                    $cookies = implode('; ', $str);
                }
            }

            $this->header = [
                'Referer'         => 'https://music.163.com/',
                'Cookie'          => $cookies,
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


    public function setProxy($value)
    {
        $this->proxy = $value;
        return $this;
    }
}
