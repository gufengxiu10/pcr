<?php

declare(strict_types=1);

namespace App\Task;

use Hyperf\Crontab\Annotation\Crontab;

/**
 * @Crontab(name="Foo", rule="* * * * *", callback="execute", memo="这是一个示例的定时任务")
 */
class FooTask
{
    public function execute()
    {
        file_put_contents(ROOT_PATH . '/1.log', 10);
    }
}
