<?php

namespace com\tyme\unit;


/**
 * 日
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class DayUnit extends MonthUnit
{
    /**
     * @var int 日
     */
    protected int $day;

    protected function __construct(int $year, int $month, int $day)
    {
        parent::__construct($year, $month);
        $this->day = $day;
    }

    /**
     * 日
     * @return int 日
     */
    function getDay(): int
    {
        return $this->day;
    }
}
