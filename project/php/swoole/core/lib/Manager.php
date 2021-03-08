<?php

declare(strict_types=1);

namespace Anng\lib;

use Swoole\Constant;
use Swoole\Process\Pool;

use function Co\run;

class Manager
{
    private $pool;
    private $batch = [];
    private $ipcType = SWOOLE_IPC_NONE;
    private $msgqueue = 0;
    private $enCo = false;

    /**
     * @name: 添加一个进程
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-08 09:41:00
     * @return {*}
     */
    public function add(callable $func, bool $co = false, callable|string $message = ''): static
    {
        $this->addBatch(1, $func, $co, $message);
        return $this;
    }

    /**
     * @name: 添加多个进程
     * @param {int} $num
     * @param {callable} $func
     * @param {bool} $co
     * @param {string} $message
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-08 13:11:12
     * @return {*}
     */
    public function addBatch(int $num, callable $func, bool $co = false, callable|string $message = ''): static
    {
        for ($i = 0; $i < $num; $i++) {
            $this->batch[] = ['start' => $func, 'co' => $co, 'message' => $message];
        }

        return $this;
    }

    /**
     * @name: 启动进程
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-08 13:11:25
     * @return {*}
     */
    public function start()
    {
        $this->pool = new Pool(count($this->batch), $this->ipcType, $this->msgqueue, $this->enCo);

        if (!in_array($this->ipcType, [SWOOLE_IPC_NONE, SWOOLE_IPC_UNIXSOCK])) {
            $this->pool->on(Constant::EVENT_MESSAGE, function (Pool $pool, $workerId) {
                ['message' => $message] = $this->batch[$workerId];
                call_user_func_array($message, [$pool, $workerId]);
            });
        }

        $this->pool->on(Constant::EVENT_WORKER_START, function (Pool $pool, $workerId) {
            ['start' => $start, 'co' => $co] = $this->batch[$workerId];
            if ($co === true && $this->enCo == false) {
                run(function () use ($start, $pool, $workerId) {
                    call_user_func_array($start, [$pool, $workerId]);
                });
            } else {
                call_user_func_array($start, [$pool, $workerId]);
            }
        });

        $this->pool->start();
    }

    public function setIPCType($val): static
    {
        $this->ipcType = $val;
        if ($this->ipcType == SWOOLE_IPC_UNIXSOCK) {
            $this->enCo = true;
        }

        return $this;
    }
}
