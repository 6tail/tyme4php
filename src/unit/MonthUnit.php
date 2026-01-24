<?php

namespace com\tyme\unit;


/**
 * 月
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class MonthUnit extends YearUnit
{
    /**
     * @var int 月
     */
    protected int $month;

    protected function __construct(int $year, int $month)
    {
        parent::__construct($year);
        $this->month = $month;
    }

    /**
     * 月
     * @return int 月
     */
    function getMonth(): int
    {
        return $this->month;
    }
}
