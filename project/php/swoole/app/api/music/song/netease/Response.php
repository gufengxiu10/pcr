<?php

declare(strict_types=1);

namespace app\api\music\song\netease;

class Response
{
    private Object $request;
    private $format;
    private $body;

    public function __construct(Object $request)
    {
        $this->request = $request;
        $this->format = new Format;
        $this->body = (string)$this->request->getBody();
    }

    public function toArray(bool $once = false)
    {
        $data = $this->format->parseAll($this->body);
        return $once === true ? $data[0] : $data;
    }

    public function url(bool $once = false)
    {
        $bool = $this->format->getData($this->body);
        return $once === true ? $bool[0] : $bool;
    }

    public function getBody()
    {
        if ($jsonData = json_decode($this->body, true)) {
            $this->body = $jsonData;
            return $jsonData;
        }
        return $this->body;
    }

    public function __call($method, $args)
    {
        if (method_exists($this->request, $method)) {
            return call_user_func_array([$this->request, $method], $args);
        }
    }
}
