<?php

use Anng\Plug\Oos\Aliyun\Objects;
use Anng\Plug\Oos\Auth;
use OSS\Model\BucketInfo;

require_once "vendor/autoload.php";

date_default_timezone_set("Asia/Shanghai");


$auth = new Auth('LTAI4GKqRce9trhJ1KGFBXT9', 'yYq0hxz2mjTP1qZtIWJCr15AptSupV');
$auth->setBucket('cic-pixiv');
$object = new Objects($auth);
$object->setFile('/images/pcr/20210109')->upload();
