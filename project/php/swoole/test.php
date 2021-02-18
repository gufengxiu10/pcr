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

$meting = new Meting();
$data = $meting->search('寒蝉');
dump(json_decode($data, true));
// run(function () {
//     App::init();
//     // Config::get('datebase')
//     $connection = Db::setConfig(Config::get('datebase'))->getConnection();
//     $sql = $connection->name('author')
//         ->getSql(false)
//         ->insertAll([
//             ['name' => 'ちふり🐄', 'img' => 'https://i.pximg.net/user-profile/img/2020/10/19/01/13/23/19532799_e47016938600a72fc5328d4cdefe4459_170.jpg'],
//             ['name' => 'さかいワカ🌎お仕事募集中', 'img' => 'https://i.pximg.net/user-profile/img/2020/10/19/01/13/23/19532799_e47016938600a72fc5328d4cdefe4459_170.jpg']
//         ]);
//     dump($sql);
// });
