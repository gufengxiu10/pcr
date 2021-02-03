<?php

declare(strict_types=1);

namespace Anng\Plug\Oos\Aliyun;

use Anng\Plug\Oos\Auth;
use OSS\Core\OssException;
use Symfony\Component\Finder\Finder;

class Objects
{

    public Auth $auth;
    private array $files = [];
    private array $errorFile = [];
    private array $resData = [];
    private array $filedError = [];

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }


    public function setFile($val)
    {

        if (is_file($val)) {
            array_push($this->files, $val);
        } elseif (is_dir($val)) {
            $finder = (new Finder)->in($val);
            foreach ($finder as $file) {
                array_push($this->files, [
                    'name'  => $file->getFilename(),
                    'path'  => $file->getRealPath()
                ]);
            }
        } else {
            return false;
        }

        return $this;
    }

    /**
     * @name: 单文件上传
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-22 17:31:28
     * @return {*}
     */
    public function upload()
    {
        foreach ($this->files as $file) {
            try {
                if (is_array($file)) {
                    $res = $this->auth->client()->uploadFile($this->auth->getBucket(), $file['name'], $file['path']);
                } else {
                    $res = $this->auth->client()->uploadFile($this->auth->getBucket(), '1.png', $file);
                }
                dump($res);
                array_push($this->resData, $res);
            } catch (OssException $e) {
                array_push($errorFile, $file);
            }
        }

        $this->retransmission();
    }


    private function retransmission()
    {
        if (!empty($errorFile)) {
            $i = 0;
            while ($i > 5) {
                foreach ($this->errorFile as $file) {
                    try {
                        if (is_array($file)) {
                            $res = $this->auth->client()->uploadFile($this->auth->getBucket(), $file['name'], $file['path']);
                        } else {
                            $res = $this->auth->client()->uploadFile($this->auth->getBucket(), '1.png', $file);
                        }

                        array_push($this->resData, $res);
                    } catch (OssException $e) {
                        if ($i == 5) {
                            array_push($this->filedError, [
                                'name' => $file['name'],
                                'path' => $file['path'],
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }
            }

            //重置
            $this->errorFile = [];
        }
    }


    // public function (Type $var = null)
    // {
    //     # code...
    // }


    public function error()
    {
        # code...
    }
}
