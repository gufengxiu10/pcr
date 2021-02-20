<?php

declare(strict_types=1);

namespace app\api\music;

class Base
{

    public function test()
    {
        $api = array(
            'method' => 'POST',
            'url'    => 'http://music.163.com/weapi/cloudsearch/pc',
            'body'   => array(
                's'      => '寒蝉',
                'type'   =>  1,
                'limit'  =>  30,
                'total'  => 'true',
                'offset' =>  0,
            ),
            'encode' => 'netease_AESCBC',
            'format' => 'result.songs',
        );
        $body = $this->aescbc($api['body']);
        $url = 'http://music.163.com/weapi/cloudsearch/pc';
        $header = [
            'Referer'         => 'https://music.163.com/',
            'Cookie'          => 'appver=1.5.9; os=osx; __remember_me=true; osver=%E7%89%88%E6%9C%AC%2010.13.5%EF%BC%88%E7%89%88%E5%8F%B7%2017F77%EF%BC%89;',
            'User-Agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/605.1.15 (KHTML, like Gecko)',
            'X-Real-IP'       => long2ip(mt_rand(1884815360, 1884890111)),
            'Accept'          => '*/*',
            'Accept-Language' => 'zh-CN,zh;q=0.8,gl;q=0.6,zh-TW;q=0.4',
            'Connection'      => 'keep-alive',
            'Content-Type'    => 'application/x-www-form-urlencoded',
        ];
        $header = array_map(function ($k, $v) {
            return $k . ': ' . $v;
        }, array_keys($header), $header);
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
            $this->raw = curl_exec($curl);
            $this->info = curl_getinfo($curl);
            $this->error = curl_errno($curl);
            $this->status = $this->error ? curl_error($curl) : '';
            if (!$this->error) {
                break;
            }
        }

        curl_close($curl);
        dump($this->raw);
        dump($this->info);
        dump($this->error);
        dump($this->status);
    }


    // const 
    public function aescbc($body)
    {
        $modulus = '157794750267131502212476817800345498121872783333389747424011531025366277535262539913701806290766479189477533597854989606803194253978660329941980786072432806427833685472618792592200595694346872951301770580765135349259590167490536138082469680638514416594216629258349130257685001248172188325316586707301643237607';
        $pubkey = '65537';
        $nonce = '0CoJUm6Qyw8W8jud';
        $vi = '0102030405060708';

        if (extension_loaded('bcmath')) {
            $skey = $this->getRandomHex(16);
        } else {
            $skey = 'B3v3kH4vRPWRJFfH';
        }

        $body =  json_encode($body);

        $body = openssl_encrypt($body, 'aes-128-cbc', $nonce, 0, $vi);
        $body = openssl_encrypt($body, 'aes-128-cbc', $skey, 0, $vi);

        $skey = strrev(utf8_encode($skey));
        $skey = $this->bchexdec($this->str2hex($skey));
        $skey = bcpowmod($skey, $pubkey, $modulus);
        $skey = $this->bcdechex($skey);

        return ['params' => $body, 'encSecKey' => $skey];
    }

    protected function getRandomHex($length)
    {
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length / 2));
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length / 2));
        }
    }

    private function str2hex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }

        return $hex;
    }

    /**
     * @name: 获得最后十进制的相加值
     * @method string bcmul() 两数两乘
     * @method string bcpow() 任意精度数字的乘方(左操作数的右操作数次方运算)
     * @method srting strval() 获取变量的字符串值
     * @method string hexdec() 十六进制转十进制
     * @method string bcadd() 两数相加
     * @Date: 2021-02-19 22:00:33
     * @return string
     */
    private function bchexdec($hex): string
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd((string)$dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }

        return $dec;
    }

    private function bcdechex($dec)
    {
        $hex = '';
        do {
            $last = bcmod($dec, '16');
            $hex = dechex((int)$last) . $hex;
            $dec = bcdiv(bcsub($dec, $last), '16');
        } while ($dec > 0);

        return $hex;
    }
}