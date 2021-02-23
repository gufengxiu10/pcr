<?php

declare(strict_types=1);

namespace app\api\music\song\netease;

class Response
{
    private Object $request;
    private $format;

    public function __construct(Object $request)
    {
        $this->request = $request;
        $this->format = new Format;
    }

    public function toArray()
    {
        return $this->format->parseAll($this->request->getBody());
    }

    public function __call($method, $args)
    {
        if (method_exists($this->request, $method)) {
            return call_user_func_array([$this->request, $method], $args);
        }
    }
}
