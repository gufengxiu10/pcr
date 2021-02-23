<?php

declare(strict_types=1);

namespace app\api\music\song\netease;

class Format
{
    public function parseAll($data): array
    {
        $format = $this->getData($data);
        return $format;
    }

    public function parseOnce($data): array
    {
        $format = $this->getData($data);
        return $format[0];
    }

    public function getData(string $data)
    {
        $format = [];
        $data = json_decode((string)$data, true);
        // dump($data);
        if (!isset($data['code']) || $data['code'] != 200) {
            throw new Excption($data['message'] ?? '请求失败');
        }

        if (isset($data['result'])) {
            $format = $this->parse($data['result']['songs']);
        }

        if (isset($data['songs'])) {
            $format = $this->parse($data['songs']);
        }

        if (isset($data['data'])) {
            foreach ($data['data'] as $value) {
                $format[] = $this->url($value);
            }
        }

        return $format;
    }

    public function parse($songs)
    {
        $format = [];
        foreach ($songs as $val) {
            $format[] = $this->format($val);
        }

        return $format;
    }


    public function format($data)
    {
        $format = [
            'name'      => $data['name'],
            'id'        => $data['id'],
            'pic_url'   => $data['al']['picUrl'] ?? '',
            'alias'     => $data['alia'],
            'author'    => $data['ar']  ?? '',
            'fee'       => $data['fee'],
        ];

        return $format;
    }

    public function url($data)
    {
        return [
            'url'   => $data['url'],
            'id'    => $data['id'],
            'fee'   => $data['fee'],
            'size'  => $data['size'],
            'type'  => $data['type'],
            'br'    => $data['br']
        ];
    }
}
