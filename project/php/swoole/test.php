<?php

declare(strict_types=1);
require_once "vendor/autoload.php";

date_default_timezone_set("Asia/Shanghai");

use Anng\lib\Reflection;
use App\Crontab\Download;

$refl = new Reflection();
$object = $refl->setDefaultMethod('run')->setMethod('download3')->instance(Download::class);
dump($object);
