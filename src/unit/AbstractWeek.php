<?php

namespace com\tyme\unit;


/**
 * 抽象周
 *
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class AbstractWeek extends WeekUnit
{
    function __construct(int $year, int $month, int $index, int $start)
    {
        parent::__construct($year, $month, $index, $start);
    }

    abstract function getAbstractMonth(): AbstractMonth;

    function next(int $n): AbstractWeek
    {
        $d = $this->index + $n;
        $m = $this->getAbstractMonth();
        if ($n > 0) {
            $weekCount = $m->getWeekCount($this->start);
            while ($d >= $weekCount) {
                $d -= $weekCount;
                $m = $m->next(1);
                if ($m->getFirstDay()->getWeek()->getIndex() != $this->start) {
                    $d += 1;
                }
                $weekCount = $m->getWeekCount($this->start);
            }
        } else {
            while ($d < 0) {
                if ($m->getFirstDay()->getWeek()->getIndex() != $this->start) {
                    $d -= 1;
                }
                $m = $m->next(-1);
                $d += $m->getWeekCount($this->start);
            }
        }
        return new static($m->getYear(), $m->getMonthValue(), $d, $this->start);
    }

    function __toString(): string
    {
        return $this->getAbstractMonth() . $this->getName();
    }
}
