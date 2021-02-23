<?php

declare(strict_types=1);

namespace app\api\music\song\netease;

class Encrypt
{
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

    public function getRandomHex($length)
    {
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length / 2));
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length / 2));
        }
    }

    public function str2hex($string)
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
    public function bchexdec($hex): string
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd((string)$dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }

        return $dec;
    }

    public function bcdechex($dec)
    {
        $hex = '';
        do {
            $last = bcmod($dec, '16');
            $hex = dechex((int)$last) . $hex;
            $dec = bcdiv(bcsub($dec, $last), '16');
        } while ($dec > 0);

        return $hex;
    }

    public function passwordEncrypt($value)
    {
        return bin2hex(md5($value));
    }

    public static function init()
    {
        return new static;
    }
}
