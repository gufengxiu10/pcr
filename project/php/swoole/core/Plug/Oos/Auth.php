<?php

namespace Anng\Plug\Oos;

use OSS\OssClient;

class Auth
{
    private string $id = '';
    private string $secret = '';
    private object $client;
    private string $bucket = '';

    public function __construct(string $id, string $secret)
    {
        $this->id = $id;
        $this->secret = $secret;
        $this->client = new OssClient($this->id, $this->secret, 'http://oss-cn-shenzhen.aliyuncs.com');
        $this->client->setConnectTimeout(20);
    }

    /**
     * @name: 获得空间名
     * @author: ANNG
     * @Date: 2021-01-22 11:26:41
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @name: 设置空间
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-22 10:27:24
     * @return {*}
     */
    public function setBucket($val)
    {
        $this->bucket = $val;
    }


    public function client()
    {
        return $this->client;
    }
}
