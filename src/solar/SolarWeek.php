<?php

namespace com\tyme\solar;


use com\tyme\unit\AbstractMonth;
use com\tyme\unit\AbstractWeek;
use InvalidArgumentException;

/**
 * 公历周
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarWeek extends AbstractWeek
{
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

    function getAbstractMonth(): AbstractMonth
    {
        return $this->getSolarMonth();
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

    function next(int $n): static
    {
        $w = parent::next($n);
        return static::fromYm($w->year, $w->month, $w->index, $this->start);
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
        return $o instanceof SolarWeek && $this->getFirstDay()->equals($o->getFirstDay());
    }
}
