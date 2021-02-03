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

    public function setTask($task)
    {
        $this->task = array_merge($this->task, $task);
        return $this;
    }

    public function run(): void
    {
        \Swoole\Timer::tick(1000, function () {
            $this->getTime();
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
            } elseif ($word['day'] != '*' && $word['month'] == '*' && $word['week'] == '*') {
                $this->timeDayCheck($word);
            } else {
                $this->timeCheck($word, 'month');
                $this->timeCheck($word, 'week');
                $this->timeCheck($word, 'day');
                $this->timeCheck($word, 'hour');
                $this->timeCheck($word, 'minute');
                $this->timeCheck($word, 'second');
                $word = $this->timeSetDefault($word);
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
        (new Reflection())->setDefaultMethod('run', ['ws' => $this->ws])
            ->setMethod($word['method'], ['ws' => $this->ws])
            ->instance($word['task']);
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
        if ($word['second'] != '*' && $this->second != $word['second']) {
            throw new Exception('时间不能过');
        } elseif (is_int($this->minute / $word['minute']) == false) {
            throw new Exception('时间不能过');
        } elseif (is_int($this->minute / $word['minute']) == true && $this->second != 0) {
            throw new Exception('时间不能过');
        }
    }

    /**
     * @name: 天的规则检测
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-16 14:31:03
     * @return {*}
     */
    public function timeDayCheck($word)
    {
        // if ($word['day'] != '*' && $word['month'] == '*' && $word['week'] == '*') {
        if (is_int($this->day / $word['day']) == false) {
            $this->error();
        } else {
            $this->timeCheck($word, 'second');
            $this->timeCheck($word, 'minute');
            $this->timeCheck($word, 'hour');
            if (is_int($this->day / $word['day']) == true) {
                if ($word['second'] == '*' && $this->second != 0) {
                    $this->error();
                }

                if ($word['minute'] == '*' && $this->minute != 0) {
                    $this->error();
                }

                if ($word['hour'] == '*' && $this->hour != 0) {
                    $this->error();
                }
            }
        }
        // }
    }

    /**
     * @name: 天的规则检测
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-16 14:31:03
     * @return {*}
     */
    public function timeWeekCheck($word)
    {
        if ($word['week'] != '*' && $word['month'] == '*') {
            if (is_int($this->week / $word['week']) == false) {
                $this->error();
            } else {
                $this->timeCheck($word, 'second');
                $this->timeCheck($word, 'minute');
                if (is_int($this->minute / $word['minute']) == true) {
                    if ($this->second == '*' && $this->second != 0) {
                        $this->error();
                    } elseif ($this->minute == '*' && $this->minute != 0) {
                        $this->error();
                    }
                }
            }
        }
    }

    public function timeSetDefault($word): array
    {
        if ($word['month'] != '*' && $word['day'] == '*') $word['day'] = 1;
        if ($word['hour'] == '*') $word['hour'] = 0;
        if ($word['minute'] == '*') $word['minute'] = 0;
        if ($word['second'] == '*') $word['second'] = 0;
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
        $this->week = $this->deleteZero(date('W', $time));
    }

    /**
     * 删除开头0
     */
    private function deleteZero($str)
    {
        return intval($str);
    }

    private  function error()
    {
        throw new Exception('时间不通过');
    }
}
