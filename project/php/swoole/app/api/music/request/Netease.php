<?php

declare(strict_types=1);

namespace app\api\music\request;

use app\api\music\encrypt\Netease as EncryptNetease;
use GuzzleHttp\Client;
use Swlib\Saber;
use Swoole\Coroutine;

class Netease
{

    private static $instance;

    const BASE_URL = 'http://music.163.com/weapi';

    public function send(string $url, $method = 'GET', array $option = [])
    {
        if ($this->check()) {
            if (class_exists(Saber::class)) {
                $client = Saber::create([
                    'headers' =>  [
                        'Referer'         => 'https://music.163.com/',
                        'Cookie'          => 'appver=1.5.9; os=osx; __remember_me=true; osver=%E7%89%88%E6%9C%AC%2010.13.5%EF%BC%88%E7%89%88%E5%8F%B7%2017F77%EF%BC%89;',
                        'User-Agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/605.1.15 (KHTML, like Gecko)',
                        'X-Real-IP'       => long2ip(mt_rand(1884815360, 1884890111)),
                        'Accept'          => '*/*',
                        'Accept-Language' => 'zh-CN,zh;q=0.8,gl;q=0.6,zh-TW;q=0.4',
                        'Connection'      => 'keep-alive',
                        'Content-Type'    => 'application/x-www-form-urlencoded'
                    ],
                ]);

                if (isset($option['data'])) {
                    $option['data'] = EncryptNetease::init()->aescbc($option['data']);
                }

                $res = $client->request([
                    'uri' => self::BASE_URL . str_replace(self::BASE_URL, '', $url),
                    'method' => $method,
                    'data' => $option['data'] ?? []
                ]);

                return $res;
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
        if (!self::$instance) {
            self::$instance = new static;
        }
        return self::$instance;
    }
}
