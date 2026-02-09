<?php

namespace com\tyme\lunar;


use com\tyme\unit\WeekUnit;
use InvalidArgumentException;

/**
 * 农历周
 * @author 6tail
 * @package com\tyme\lunar
 */
class LunarWeek extends WeekUnit
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
        $m = LunarMonth::fromYm($year, $month);
        if ($index >= $m->getWeekCount($start)) {
            throw new InvalidArgumentException(sprintf('illegal lunar week index: %d in month: %s', $index, $m));
        }
    }

    static function fromYm(int $year, int $month, int $index, int $start): static
    {
        return new static($year, $month, $index, $start);
    }

    /**
     * 农历月
     *
     * @return LunarMonth 农历月
     */
    function getLunarMonth(): LunarMonth
    {
        return LunarMonth::fromYm($this->year, $this->month);
    }

    function getName(): string
    {
        return static::$NAMES[$this->index];
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->getLunarMonth(), $this->getName());
    }

    function next(int $n): static
    {
        if ($n === 0) {
            return static::fromYm($this->getYear(), $this->getMonth(), $this->index, $this->start);
        }
        $d = $this->index + $n;
        $m = $this->getLunarMonth();
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
        return static::fromYm($m->getYear(), $m->getMonthWithLeap(), $d, $this->start);
    }

    /**
     * 本周第1天
     *
     * @return LunarDay 公历日
     */
    function getFirstDay(): LunarDay
    {
        $firstDay = LunarDay::fromYmd($this->year, $this->month, 1);
        return $firstDay->next($this->index * 7 - $this->indexOf($firstDay->getWeek()->getIndex() - $this->start, null, 7));
    }

    /**
     * 本周农历日列表
     *
     * @return LunarDay[] 农历日列表
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
        return $o instanceof LunarWeek && $this->getFirstDay() . $this->equals($o->getFirstDay());
    }
}
