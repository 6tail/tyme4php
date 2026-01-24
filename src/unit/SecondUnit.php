<?php

namespace com\tyme\unit;


use InvalidArgumentException;

/**
 * 秒
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class SecondUnit extends DayUnit
{
    /**
     * @var int 时
     */
    protected int $hour;

    /**
     * @var int 分
     */
    protected int $minute;

    /**
     * @var int 秒
     */
    protected int $second;

    protected function __construct(int $year, int $month, int $day, $hour, $minute, $second)
    {
        parent::__construct($year, $month, $day);
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    static function validate($year, $month, $day, $hour, $minute, $second): void
    {
        if ($hour < 0 || $hour > 23) {
            throw new InvalidArgumentException(sprintf('illegal hour: %d', $hour));
        }
        if ($minute < 0 || $minute > 59) {
            throw new InvalidArgumentException(sprintf('illegal minute: %d', $minute));
        }
        if ($second < 0 || $second > 59) {
            throw new InvalidArgumentException(sprintf('illegal second: %d', $second));
        }
    }

    /**
     * 时
     * @return int 时
     */
    function getHour(): int
    {
        return $this->hour;
    }

    /**
     * 分
     * @return int 分
     */
    function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * 秒
     * @return int 秒
     */
    function getSecond(): int
    {
        return $this->second;
    }
}
