<?php

namespace com\tyme\unit;


use com\tyme\culture\Week;

/**
 * 抽象日
 *
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class AbstractDay extends DayUnit
{
    function __construct(int $year, int $month, int $day)
    {
        parent::__construct($year, $month, $day);
    }

    /**
     * 星期
     *
     * @return Week 星期
     */
    abstract function getWeek(): Week;
}
