<?php

namespace com\tyme\unit;


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
        parent::validateRange($hour, 0, 23, 'hour');
        parent::validateRange($minute, 0, 59, 'minute');
        parent::validateRange($second, 0, 59, 'second');
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

    /**
     * 当天秒数
     * @return int 当天秒数
     */
    function getSecondsInDay(): int
    {
        return $this->hour * 3600 + $this->minute * 60 + $this->second;
    }

    protected function getCompareIndex(): int
    {
        return parent::getCompareIndex() * 86400 + $this->getSecondsInDay();
    }
}
