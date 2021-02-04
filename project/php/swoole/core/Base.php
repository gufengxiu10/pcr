<?php

use Anng\lib\App;
use Anng\lib\Container;
use Anng\lib\Crontab;
use Anng\lib\Env;
use Anng\lib\Config;
use Anng\lib\Connect;
use Anng\lib\Facade;
use Anng\lib\Redis;
use Anng\lib\Db;
use Symfony\Component\Finder\Finder;

require_once "vendor/autoload.php";

define('ROOT_PATH', dirname(__DIR__));

$container = new Container;
$container->bind([
    'App'       => App::class,
    'Facade'    => Facade::class,
    'Config'    => Config::class,
    'Redis'     => Redis::class,
    'Env'       => Env::class,
    'Db'        => Db::class,
    'Crontab'   => Crontab::class,
    'Finder'    => Finder::class,
    'Connect'   => Connect::class
]);

require_once 'Helper.php';
