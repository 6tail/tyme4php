<?php

namespace com\tyme\unit;


/**
 * 抽象年
 *
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class AbstractYear extends YearUnit
{
    function __construct(int $year)
    {
        parent::__construct($year);
    }

    /**
     * 月数
     *
     * @return int 月数
     */
    function getMonthCount(): int
    {
        return $this->getLeapMonth() < 1 ? 12 : 13;
    }

    /**
     * 闰月
     *
     * @return int 闰月数字，1代表闰1月，0代表无闰月
     */
    function getLeapMonth(): int
    {
        return 0;
    }

    function getName(): string
    {
        return $this->year . '年';
    }
}
