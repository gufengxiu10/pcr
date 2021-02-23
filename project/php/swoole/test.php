<?php

declare(strict_types=1);

require_once "vendor/autoload.php";
require_once './core/Base.php';

use Anng\lib\facade\App;
use Anng\lib\facade\Config;
use Anng\lib\facade\Container;
use Anng\lib\facade\Db;
use Metowolf\Meting;
use Swoole\Process\Manager;

use function Co\run;

date_default_timezone_set("Asia/Shanghai");
run(function () {
    $client = new \app\api\music\Base();
    $client->test();
});
