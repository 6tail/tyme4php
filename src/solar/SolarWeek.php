<?php

namespace com\tyme\solar;


use com\tyme\unit\WeekUnit;
use InvalidArgumentException;

/**
 * 公历周
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarWeek extends WeekUnit
{
    static array $NAMES = ['第一周', '第二周', '第三周', '第四周', '第五周', '第六周'];

    protected function __construct(int $year, int $month, int $index, int $start)
    {
        self::validate($year, $month, $index, $start);
        parent::__construct($year, $month, $index, $start);
    }

    static function validate(int $year, int $month, int $index, int $start): void
    {
        parent::validate($year, $month, $index, $start);
        $m = SolarMonth::fromYm($year, $month);
        if ($index >= $m->getWeekCount($start)) {
            throw new InvalidArgumentException(sprintf('illegal solar week index: %d in month: %s', $index, $m));
        }
    }

    static function fromYm(int $year, int $month, int $index, int $start): static
    {
        return new static($year, $month, $index, $start);
    }

    /**
     * 公历月
     *
     * @return SolarMonth 公历月
     */
    function getSolarMonth(): SolarMonth
    {
        return SolarMonth::fromYm($this->year, $this->month);
    }

    /**
     * 位于当年的索引
     *
     * @return int 索引
     */
    function getIndexInYear(): int
    {
        $i = 0;
        $firstDay = $this->getFirstDay();
        // 今年第1周
        $w = static::fromYm($this->year, 1, 0, $this->start);
        while (!$w->getFirstDay()->equals($firstDay)) {
            $w = $w->next(1);
            $i += 1;
        }
        return $i;
    }

    function getName(): string
    {
        return static::$NAMES[$this->index];
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->getSolarMonth(), $this->getName());
    }

    function next(int $n): static
    {
        $d = $this->index;
        $m = $this->getSolarMonth();
        if ($n > 0) {
            $d += $n;
            $weekCount = $m->getWeekCount($this->start);
            while ($d >= $weekCount) {
                $d -= $weekCount;
                $m = $m->next(1);
                if ($m->getFirstDay()->getWeek()->getIndex() != $this->start) {
                    $d += 1;
                }
                $weekCount = $m->getWeekCount($this->start);
            }
        } else if ($n < 0) {
            $d += $n;
            while ($d < 0) {
                if ($m->getFirstDay()->getWeek()->getIndex() != $this->start) {
                    $d -= 1;
                }
                $m = $m->next(-1);
                $d += $m->getWeekCount($this->start);
            }
        }
        return static::fromYm($m->getYear(), $m->getMonth(), $d, $this->start);
    }

    /**
     * 本周第1天
     *
     * @return SolarDay 公历日
     */
    function getFirstDay(): SolarDay
    {
        $firstDay = SolarDay::fromYmd($this->year, $this->month, 1);
        return $firstDay->next($this->index * 7 - $this->indexOf($firstDay->getWeek()->getIndex() - $this->start, null, 7));
    }

    /**
     * 本周公历日列表
     *
     * @return SolarDay[] 公历日列表
     */
    function getDays(): array
    {
        $l = array();
        $d = $this->getFirstDay();
        $l[] = $d;
        for ($i = 1; $i < 7; $i++) {
            $l[] = $d->next($i);
        }
        return $l;
    }

    /**
     * @param mixed $o 对象
     * @return bool true/false
     */
    function equals(mixed $o): bool
    {
        return $o instanceof SolarWeek && $this->getFirstDay() . $this->equals($o->getFirstDay());
    }
}
