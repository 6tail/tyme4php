<?php

namespace com\tyme\solar;


use com\tyme\AbstractTyme;
use com\tyme\jd\JulianDay;
use com\tyme\lunar\LunarHour;
use InvalidArgumentException;

/**
 * 公历时刻
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarTime extends AbstractTyme
{
    /**
     * @var SolarDay 公历日
     */
    protected SolarDay $day;

    /**
     * @var int 时
     */
    protected int $hour;

    /**
     * @var int 分
     */
    protected int $minute;

    /**
     * @var int 秒
     */
    protected int $second;

    protected function __construct(int $year, int $month, int $day, int $hour, int $minute, int $second)
    {
        if ($hour < 0 || $hour > 23) {
            throw new InvalidArgumentException(sprintf('illegal hour: %d', $hour));
        }
        if ($minute < 0 || $minute > 59) {
            throw new InvalidArgumentException(sprintf('illegal minute: %d', $minute));
        }
        if ($second < 0 || $second > 59) {
            throw new InvalidArgumentException(sprintf('illegal second: %d', $second));
        }
        $this->day = SolarDay::fromYmd($year, $month, $day);
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    static function fromYmdHms(int $year, int $month, int $day, int $hour, int $minute, int $second): static
    {
        return new static($year, $month, $day, $hour, $minute, $second);
    }

    /**
     * 日
     *
     * @return SolarDay 日
     */
    function getDay(): SolarDay
    {
        return $this->day;
    }

    /**
     * 时
     *
     * @return int 时
     */
    function getHour(): int
    {
        return $this->hour;
    }

    /**
     * 分
     *
     * @return int 分
     */
    function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * 秒
     *
     * @return int 秒
     */
    function getSecond(): int
    {
        return $this->second;
    }

    function getName(): string
    {
        return sprintf('%02d:%02d:%02d', $this->hour, $this->minute, $this->second);
    }

    function __toString(): string
    {
        return sprintf('%s %s', $this->day, $this->getName());
    }

    /**
     * 是否在指定公历时刻之前
     *
     * @param SolarTime $target 公历时刻
     * @return bool true/false
     */
    function isBefore(SolarTime $target): bool
    {
        if (!$this->day->equals($target->getDay())) {
            return $this->day->isBefore($target->getDay());
        }
        if ($this->hour != $target->getHour()) {
            return $this->hour < $target->getHour();
        }
        return $this->minute != $target->getMinute() ? $this->minute < $target->getMinute() : $this->second < $target->getSecond();
    }

    /**
     * 是否在指定公历时刻之后
     *
     * @param SolarTime $target 公历时刻
     * @return true/false
     */
    function isAfter(SolarTime $target): bool
    {
        if (!$this->day->equals($target->getDay())) {
            return $this->day->isAfter($target->getDay());
        }
        if ($this->hour != $target->getHour()) {
            return $this->hour > $target->getHour();
        }
        return $this->minute != $target->getMinute() ? $this->minute > $target->getMinute() : $this->second > $target->getSecond();
    }

    /**
     * 节气
     *
     * @return SolarTerm 节气
     */
    function getTerm(): SolarTerm
    {
        $m = $this->day->getMonth();
        $y = $m->getYear()->getYear();
        $i = $m->getMonth() * 2;
        if ($i == 24) {
            $y += 1;
            $i = 0;
        }
        $term = SolarTerm::fromIndex($y, $i);
        while ($this->isBefore($term->getJulianDay()->getSolarTime())) {
            $term = $term->next(-1);
        }
        return $term;
    }

    /**
     * 儒略日
     *
     * @return JulianDay 儒略日
     */
    function getJulianDay(): JulianDay
    {
        $month = $this->day->getMonth();
        return JulianDay::fromYmdHms($month->getYear()->getYear(), $month->getMonth(), $this->day->getDay(), $this->hour, $this->minute, $this->second);
    }

    /**
     * 公历时刻相减，获得相差秒数
     *
     * @param SolarTime $target 公历时刻
     * @return int 秒数
     */
    function subtract(SolarTime $target): int
    {
        $days = $this->day->subtract($target->getDay());
        $cs = $this->hour * 3600 + $this->minute * 60 + $this->second;
        $ts = $target->getHour() * 3600 + $target->getMinute() * 60 + $target->getSecond();
        $seconds = $cs - $ts;
        if ($seconds < 0) {
            $seconds += 86400;
            $days--;
        }
        $seconds += $days * 86400;
        return $seconds;
    }

    /**
     * 推移
     *
     * @param int $n 推移秒数
     * @return SolarTime 公历时刻
     */
    function next(int $n): SolarTime
    {
        if ($n == 0) {
            $m = $this->day->getMonth();
            return self::fromYmdHms($m->getYear()->getYear(), $m->getMonth(), $this->day->getDay(), $this->hour, $this->minute, $this->second);
        }
        $ts = $this->second + $n;
        $tm = $this->minute + intdiv($ts, 60);
        $ts %= 60;
        if ($ts < 0) {
            $ts += 60;
            $tm -= 1;
        }
        $th = $this->hour + intdiv($tm, 60);
        $tm %= 60;
        if ($tm < 0) {
            $tm += 60;
            $th -= 1;
        }
        $td = intdiv($th, 24);
        $th %= 24;
        if ($th < 0) {
            $th += 24;
            $td -= 1;
        }

        $d = $this->day->next($td);
        $m = $d->getMonth();
        return self::fromYmdHms($m->getYear()->getYear(), $m->getMonth(), $d->getDay(), $th, $tm, $ts);
    }

    /**
     * 时辰
     *
     * @return LunarHour 农历时辰
     */
    function getLunarHour(): LunarHour
    {
        $d = $this->day->getLunarDay();
        $m = $d->getMonth();
        return LunarHour::fromYmdHms($m->getYear()->getYear(), $m->getMonthWithLeap(), $d->getDay(), $this->hour, $this->minute, $this->second);
    }

}
