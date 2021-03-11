<?php

declare(strict_types=1);

use app\crontab\ToDayPrice;

use function Co\run;

require_once "vendor/autoload.php";

use Anng\lib\Container;
use Anng\lib\App;
use Anng\lib\Crontab;
use Anng\lib\Env;
use Anng\lib\Config;
use Anng\lib\Connect;
use Anng\lib\Facade;
use Anng\lib\Redis;
use Anng\lib\Db;
use Anng\lib\Info;
use Anng\lib\Table;
use Anng\lib\Annotations;
use Anng\lib\facade\Config as FacadeConfig;
use Anng\lib\Messages;
use Symfony\Component\Finder\Finder;

$container = new Container;
$container->bind([
    'App'           => App::class,
    'Facade'        => Facade::class,
    'Config'        => Config::class,
    'Redis'         => Redis::class,
    'Env'           => Env::class,
    'Db'            => Db::class,
    'Crontab'       => Crontab::class,
    'Finder'        => Finder::class,
    'Connect'       => Connect::class,
    'Table'         => Table::class,
    'Info'          => Info::class,
    'Annotations'   => Annotations::class,
    'Messages'      => Messages::class
]);

$files = glob(__DIR__ . '/config/*.php');

foreach ($files as $file) {
    FacadeConfig::load($file, pathinfo($file, PATHINFO_FILENAME));
}

run(function () {
    $price = new ToDayPrice();
    $price->run();
});
