<?php

require_once dirname(__DIR__, 2) . "/vendor/autoload.php";

use app\api\music\song\netease\Netease;
use PHPUnit\Framework\TestCase;

use function Co\run;

final class User extends TestCase
{
    public function testInfo()
    {
        run(function () {
            $res = (new Netease)->module('user')->info();
            $this->assertArrayHasKey('code', $res->getBody());
        });
    }
}
