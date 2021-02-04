<?php

declare(strict_types=1);
require_once "vendor/autoload.php";

date_default_timezone_set("Asia/Shanghai");

use Anng\Plug\Oos\Aliyun\Objects;
use Anng\Plug\Oos\Auth;
use App\Crontab\Download;
use Predis\Client as PredisClient;
use Swlib\SaberGM;

\Swoole\Coroutine::set([
    'hook_flags' => SWOOLE_HOOK_CURL
]);

Co\run(function () {
    $d = new Download();
    $d->run();
});
