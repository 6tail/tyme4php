<?php

namespace com\tyme\unit;


/**
 * 抽象月
 *
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class AbstractMonth extends MonthUnit
{
    function __construct(int $year, int $month)
    {
        parent::__construct($year, $month);
    }

    /**
     * 月
     *
     * @return int 月，当月为闰月时，返回负数，如-2代表闰二月
     */
    function getMonthValue(): int
    {
        return $this->month;
    }

    /**
     * 周数
     *
     * @param int $start 起始星期，1234560分别代表星期一至星期天
     * @return int 周数
     */
    function getWeekCount(int $start): int
    {
        return (int)ceil(($this->indexOf($this->getFirstDay()->getWeek()->getIndex() - $start, null, 7) + $this->getDayCount()) / 7);
    }

    /**
     * 天数
     *
     * @return int 天数
     */
    abstract function getDayCount(): int;

    /**
     * 本月第1天
     *
     * @return AbstractDay 抽象日
     */
    abstract function getFirstDay(): AbstractDay;

    /**
     * 抽象年
     *
     * @return AbstractYear 抽象年
     */
    abstract function getAbstractYear(): AbstractYear;

    function __toString(): string
    {
        return $this->getAbstractYear() . $this->getName();
    }
}
