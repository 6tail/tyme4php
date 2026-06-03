<?php

namespace com\tyme\festival;


use com\tyme\event\Event;
use com\tyme\solar\SolarDay;

/**
 * 公历现代节日
 * @author 6tail
 * @package com\tyme\festival
 */
class SolarFestival extends AbstractFestival
{
    static array $NAMES = ['元旦', '妇女节', '植树节', '劳动节', '青年节', '儿童节', '建党节', '建军节', '教师节', '国庆节'];

    static string $DATA = '0VV__0Ux0Xc__0Ux0Xg__0_Q0ZV__0Ux0ZY__0Ux0aV__0Ux0bV__0Uo0cV__0Ug0de__0_V0eV__0Ux';

    protected function __construct(int $index, Event $event, SolarDay $day)
    {
        parent::__construct($index, $event, $day);
    }

    static function fromIndex(int $year, int $index): ?static
    {
        if ($index < 0 || $index >= count(static::$NAMES)) {
            return null;
        }
        $start = $index * 8;
        $e = new Event(static::$NAMES[$index], '@' . substr(static::$DATA, $start, 8));
        if ($year < $e->getStartYear()) {
            return null;
        }
        return new static($index, $e, SolarDay::fromYmd($year, $e->getValue(2), $e->getValue(3)));
    }

    static function fromYmd(int $year, int $month, int $day): ?static
    {
        $d = SolarDay::fromYmd($year, $month, $day);
        for ($i = 0, $j = count(static::$NAMES); $i < $j; $i++) {
            $start = $i * 8;
            $e = new Event(static::$NAMES[$i], '@' . substr(static::$DATA, $start, 8));
            if ($d->getYear() >= $e->getStartYear() && $d->getMonth() === $e->getValue(2) && $d->getDay() === $e->getValue(3)) {
                return new static($i, $e, $d);
            }
        }
        return null;
    }

    function next(int $n): static
    {
        $size = count(static::$NAMES);
        $i = $this->index + $n;
        return static::fromIndex(intdiv($this->day->getYear() * $size + $i, $size), $this->indexOf($i, null, $size));
    }

    /**
     * 公历日
     * @return SolarDay 公历日
     */
    function getDay(): SolarDay
    {
        return $this->day;
    }

    /**
     * 起始年
     *
     * @return int 年
     */
    function getStartYear(): int
    {
        return $this->event->getStartYear();
    }
}
