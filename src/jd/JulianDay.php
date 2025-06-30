<?php

namespace com\tyme\jd;


use com\tyme\AbstractTyme;
use com\tyme\culture\Week;
use com\tyme\solar\SolarDay;
use com\tyme\solar\SolarTime;

/**
 * 儒略日
 * @author 6tail
 * @package com\tyme\jd
 */
class JulianDay extends AbstractTyme
{
    /**
     * @var int 2000年儒略日数(2000-1-1 12:00:00 UTC)
     */
    const J2000 = 2451545;

    /**
     * @var float 儒略日
     */
    protected float $day;

    protected function __construct($day)
    {
        $this->day = $day;
    }

    static function fromJulianDay($day): static
    {
        return new static($day);
    }

    static function fromYmdHms(int $year, int $month, int $day, int $hour, int $minute, int $second): static
    {
        $d = $day + (($second / 60 + $minute) / 60 + $hour) / 24;
        $n = 0;
        $g = $year * 372 + $month * 31 + (int)$d >= 588829;
        if ($month <= 2) {
            $month += 12;
            $year--;
        }
        if ($g) {
            $n = intdiv($year, 100);
            $n = 2 - $n + intdiv($n, 4);
        }
        return static::fromJulianDay((int)(365.25 * ($year + 4716)) + (int)(30.6001 * ($month + 1)) + $d + $n - 1524.5);
    }

    /**
     * 儒略日
     *
     * @return float 儒略日
     */
    function getDay(): float
    {
        return $this->day;
    }

    function getName(): string
    {
        return $this->day . '';
    }

    function next(int $n): static
    {
        return static::fromJulianDay($this->day + $n);
    }

    /**
     * 公历日
     *
     * @return SolarDay 公历日
     */
    function getSolarDay(): SolarDay
    {
        return $this->getSolarTime()->getSolarDay();
    }

    /**
     * 公历时刻
     *
     * @return SolarTime 公历时刻
     */
    function getSolarTime(): SolarTime
    {
        $d = (int)($this->day + 0.5);
        $f = $this->day + 0.5 - $d;

        if ($d >= 2299161) {
            $c = (int)(($d - 1867216.25) / 36524.25);
            $d += 1 + $c - intdiv($c, 4);
        }
        $d += 1524;
        $year = (int)(($d - 122.1) / 365.25);
        $d -= (int)(365.25 * $year);
        $month = (int)($d / 30.601);
        $d -= (int)(30.601 * $month);
        $day = $d;
        if ($month > 13) {
            $month -= 12;
        } else {
            $year -= 1;
        }
        $month -= 1;
        $year -= 4715;
        $f *= 24;
        $hour = (int)$f;

        $f -= $hour;
        $f *= 60;
        $minute = (int)$f;

        $f -= $minute;
        $f *= 60;
        $second = (int)round($f);
        return $second < 60 ? SolarTime::fromYmdHms($year, $month, $day, $hour, $minute, $second) : SolarTime::fromYmdHms($year, $month, $day, $hour, $minute, $second - 60)->next(60);
    }

    /**
     * 星期
     *
     * @return Week 星期
     */
    function getWeek(): Week
    {
        return Week::fromIndex((int)($this->day + 0.5) + 7000001);
    }

    /**
     * 儒略日相减
     * @param JulianDay $target 儒略日
     * @return float 差
     */
    function subtract(JulianDay $target): float
    {
        return $this->day - $target->getDay();
    }

}
