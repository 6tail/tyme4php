<?php

namespace com\tyme\unit;


/**
 * 支持闰月的月份抽象
 *
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class AbstractLeapMonth extends AbstractMonth
{
    /**
     * @var bool 是否闰月
     */
    protected bool $leap;

    function __construct(int $year, int $month)
    {
        parent::__construct($year, abs($month));
        $this->leap = $month < 0;
    }

    /**
     * 是否闰月
     *
     * @return bool true/false
     */
    function isLeap(): bool
    {
        return $this->leap;
    }

    /**
     * 月
     *
     * @return int 月，当月为闰月时，返回负数
     * @deprecated
     * @see getMonthValue()
     */
    function getMonthWithLeap(): int
    {
        return $this->getMonthValue();
    }

    function getMonthValue(): int
    {
        return $this->leap ? -$this->month : $this->month;
    }

    /**
     * 位于当年的索引(0-12)
     *
     * @return int 索引
     */
    function getIndexInYear(): int
    {
        $index = $this->month - 1;
        if ($this->leap) {
            $index += 1;
        } else {
            $leapMonth = $this->getAbstractYear()->getLeapMonth();
            if ($leapMonth > 0 && $this->month > $leapMonth) {
                $index += 1;
            }
        }
        return $index;
    }

    function next(int $n): AbstractLeapMonth
    {
        $ty = $this->year;
        $tm = $this->getMonthValue();
        if ($n !== 0) {
            $m = $this->getIndexInYear() + 1 + $n;
            $y = $this->getAbstractYear();
            if ($n > 0) {
                $monthCount = $y->getMonthCount();
                while ($m > $monthCount) {
                    $m -= $monthCount;
                    $y = $y->next(1);
                    $monthCount = $y->getMonthCount();
                }
            } else {
                while ($m <= 0) {
                    $y = $y->next(-1);
                    $m += $y->getMonthCount();
                }
            }
            $leap = false;
            $leapMonth = $y->getLeapMonth();
            if ($leapMonth > 0) {
                if ($m === $leapMonth + 1) {
                    $leap = true;
                }
                if ($m > $leapMonth) {
                    $m--;
                }
            }
            $ty = $y->year;
            $tm = $leap ? -$m : $m;
        }
        return new static($ty, $tm);
    }
}
