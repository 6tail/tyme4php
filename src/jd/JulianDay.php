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
            $n = (int)($year * 0.01);
            $n = 2 - $n + (int)($n * 0.25);
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
        $n = (int)($this->day + 0.5);
        $f = $this->day + 0.5 - $n;

        if ($n >= 2299161) {
            $c = (int)(($n - 1867216.25) / 36524.25);
            $n += 1 + $c - intdiv($c, 4);
        }
        $n += 1524;
        $y = (int)(($n - 122.1) / 365.25);
        $n -= (int)(365.25 * $y);
        $m = (int)($n / 30.601);
        $n -= (int)(30.601 * $m);
        $d = $n;
        if ($m > 13) {
            $m -= 12;
        } else {
            $y -= 1;
        }
        $m -= 1;
        $y -= 4715;
        $f *= 24;
        $hour = (int)$f;

        $f -= $hour;
        $f *= 60;
        $minute = (int)$f;

        $f -= $minute;
        $f *= 60;
        $second = (int)round($f);
        return $second < 60 ? SolarTime::fromYmdHms($y, $m, $d, $hour, $minute, $second) : SolarTime::fromYmdHms($y, $m, $d, $hour, $minute, $second - 60)->next(60);
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
