<?php

declare(strict_types=1);

namespace app\api\music;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Filesystem\Filesystem;

class Cache
{

    private static $instance;

    //缓存目录
    protected $dir;
    private string $namespance = '';

    public static function init()
    {
        if (!self::$instance) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    private function getCache()
    {
        return new FilesystemAdapter('netease', 2 * 60, $this->getCacheDir());
    }

    public  function set(string $cacheName, $value, array $option = [])
    {
        $cache = $this->getCache();
        $key = $cacheName;

        $name = $cache->getItem($key);
        $name->set($value);
        isset($option['expires']) ? $name->expiresAfter($option['expires']) : '';
        $cache->save($name);
        unset($cache);
        unset($name);
    }

    public function get(string $cacheName)
    {
        $cache = $this->getCache();

        $name = $cache->getItem($cacheName);
        $data = $name->get();
        if (is_string($data) && $jsonData = json_decode($data, true)) {
            $data = $jsonData;
        }
        unset($cache);
        unset($name);
        return $data;
    }

    public function has(string $cacheName)
    {
        $cache = $this->getCache();
        // if (strpos($cacheName, '.') !== false) {
        //     $cacheName = explode('.', $cacheName);
        //     $name = $cache->getItem($cacheName[0]);
        //     if (!$name->isHit()) {
        //         return false;
        //     }

        //     $getData = $name->get();
        //     if (isset($getData[$cacheName[1]]) == null) {
        //         return false;
        //     }
        // } else {
        //     $name = $cache->getItem($cacheName);
        //     if (!$name->isHit()) {
        //         return false;
        //     }
        // }
        $name = $cache->getItem($cacheName);
        if (!$name->isHit()) {
            return false;
        }
        return true;
    }

    /**
     * @name: 清除全部缓存
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-24 17:16:01
     * @return {*}
     */
    public function clear()
    {
        $fs = new Filesystem;
        $path = $this->getCacheDir();
        if ($fs->exists($path)) {
            $fs->remove($path);
        }
    }

    public function setDir($path)
    {
        $this->dir = $path;
        return $this;
    }

    protected function getCacheDir()
    {
        if (!$this->dir) {
            $this->dir = __DIR__ . '/cache';
        }
        return $this->dir;
    }
}
