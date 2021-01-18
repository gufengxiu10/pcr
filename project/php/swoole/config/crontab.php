<?php

use App\Crontab\Download;
use App\Crontab\ToDayPrice;

return [
    [
        'id'          => 1, // ID
        'title'       => '任务1', // 任务名
        'second'      => '*', // 秒
        'minute'      => '*', // 分钟
        'hour'        => '*', // 小时
        'day'         => '1', // 天数
        'month'       => '*', // 月份
        'week'        => '*', // 星期
        'task'        => Download::class, // 任务
        'method'      => 'download',
        'bin_log'     => true, // 是否记录日志
    ],
    [
        'id'          => 1, // ID
        'title'       => '任务1', // 任务名
        'second'      => '*', // 秒
        'minute'      => '*', // 分钟
        'hour'        => '*', // 小时
        'day'         => '*', // 天数
        'month'       => '*', // 月份
        'week'        => '*', // 星期
        'task'        => Download::class, // 任务
        'method'      => 'check',
        'bin_log'     => true, // 是否记录日志
    ]
];
