<?php

declare(strict_types=1);

namespace app\api\music\song\netease;

class Format
{
    public function parseAll($data): array
    {
        $format = [];
        $data = json_decode((string)$data, true);
        if (isset($data['result'])) {
            $format = $this->parse($data['result']['songs']);
        }

        if (isset($data['songs'])) {
            $format = $this->parse($data['songs']);
        }

        return $format;
    }

    public function parseOnce($data): array
    {
        $data = json_decode((string)$data, true);
        if (isset($data['result'])) {
            $format = $this->parse($data['result']['songs']);
        }

        if (isset($data['songs'])) {
            $format = $this->parse($data['songs']);
        }

        return $format[0];
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
            // 'is_vip'    => $data['fee'] == 1 ? 1 : 0,
        ];

        return $format;
    }
}
