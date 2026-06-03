<?php

namespace com\tyme\unit;


use com\tyme\AbstractTyme;

/**
 * 年
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class YearUnit extends AbstractTyme
{
    /**
     * @var int 年
     */
    protected int $year;

    protected function __construct(int $year)
    {
        $this->year = $year;
    }

    /**
     * 年
     * @return int 年
     */
    function getYear(): int
    {
        return $this->year;
    }

    /**
     * 用于比较大小的索引
     *
     * @return int 索引
     */
    protected function getCompareIndex(): int
    {
        return $this->year * 10000;
    }
}
