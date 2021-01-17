<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\traits\ws;
use \Exception;

class Crontab
{
    use ws;

    private array $task = [];
    private int $second;
    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->getTask();
        $this->run();
    }

    public function getTask()
    {
        $task = $this->app->config->get('crontab');
        $this->task = array_merge($this->task, $task);
    }

    public function run(): void
    {
        \Swoole\Timer::tick(1000, function ($timeId) {
            $this->getTime();
            dump($this->second);
            foreach ($this->task as $val) {
                $this->send($val);
            }
        });
    }

    public function send($word): void
    {
        try {
            if (
                $word['second'] != '*'
                && $word['minute'] == '*'
                && $word['hour'] == '*'
                && $word['month'] == '*'
                && $word['week'] == '*'
            ) {
                $this->timeSecondCheck($word);
            } else if (
                $word['minute'] != '*'
                && $word['hour'] == '*'
                && $word['month'] == '*'
                && $word['week'] == '*'
            ) {
                $this->timeMinuteCheck($word);
            } else {
                $word = $this->timeSetDefault($word);
                $this->timeCheck($word, 'month');
                $this->timeCheck($word, 'week');
                $this->timeCheck($word, 'day');
                $this->timeCheck($word, 'hour');
                $this->timeCheck($word, 'minute');
                $this->timeCheck($word, 'second');
            }
        } catch (\Throwable $th) {
            return;
        }

        swoole_timer_after(10, function () use ($word) {
            $this->taskClass($word);
        });
    }

    /**
     * @name: 实例化相关类
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-16 14:54:06
     * @return {*}
     */
    public function taskClass($word)
    {
        $refl = new \ReflectionClass($word['task']);
        $object = $refl->newInstance();
        if (isset($word['method']) && $refl->hasMethod($word['method'])) {
            $method = $word['method'];
            $object->$method($this->ws);
        } else {
            $object->run($this->ws);
        }
    }

    /**
     * @name: 时间检测
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-16 14:31:03
     * @return {*}
     */
    public function timeCheck($word, $type)
    {
        if ($word[$type] != '*' && $this->$type != $word[$type]) {
            throw new Exception('时间不能过');
        }
    }

    /**
     * @name: 秒的规则检测
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-16 14:31:03
     * @return {*}
     */
    public function timeSecondCheck($word)
    {
        if (is_int($this->second / $word['second']) === false) {
            throw new Exception('时间不能过');
        }
    }

    /**
     * @name: 分的规则检测
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-16 14:31:03
     * @return {*}
     */
    public function timeMinuteCheck($word)
    {
        if (
            $word['second'] != '*'
            && $this->second != $word['second']
        ) {
            throw new Exception('时间不能过');
        } elseif (is_int($this->minute / $word['minute']) === false && $this->second != 0) {
            throw new Exception('时间不能过');
        }
    }

    public function timeSetDefault($word): array
    {
        if ($word['month'] != '*' && $word['day'] == '*') $word['day'] = 1;
        if ($word['month'] != '*' && $word['hour'] == '*') $word['hour'] = 0;
        if ($word['month'] != '*' && $word['minute'] == '*') $word['minute'] = 0;
        if ($word['month'] != '*' && $word['second'] == '*') $word['second'] = 1;
        return $word;
    }

    /**
     * @name: 获得当前时间
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-16 14:18:45
     * @return {*}
     */
    public function getTime(): void
    {
        $time = time();
        $this->second = $this->deleteZero(date('s', $time));
        $this->minute = $this->deleteZero(date('i', $time));
        $this->hour = $this->deleteZero(date('H', $time));
        $this->day = $this->deleteZero(date('d', $time));
        $this->month = $this->deleteZero(date('m', $time));
        $this->week = $this->deleteZero(date('2', $time));
    }

    /**
     * 删除开头0
     */
    private function deleteZero($str)
    {
        return intval($str);
    }
}
