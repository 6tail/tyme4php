<?php

namespace com\tyme\solar;


use com\tyme\culture\Phase;
use com\tyme\culture\phenology\Phenology;
use com\tyme\jd\JulianDay;
use com\tyme\lunar\LunarHour;
use com\tyme\sixtycycle\SixtyCycleHour;
use com\tyme\unit\SecondUnit;

/**
 * 公历时刻
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarTime extends SecondUnit
{
    protected function __construct(int $year, int $month, int $day, int $hour, int $minute, int $second)
    {
        self::validate($year, $month, $day, $hour, $minute, $second);
        parent::__construct($year, $month, $day, $hour, $minute, $second);
    }

    static function validate($year, $month, $day, $hour, $minute, $second): void
    {
        parent::validate($year, $month, $day, $hour, $minute, $second);
        SolarDay::validate($year, $month, $day);
    }

    static function fromYmdHms(int $year, int $month, int $day, int $hour, int $minute, int $second): static
    {
        return new static($year, $month, $day, $hour, $minute, $second);
    }

    /**
     * 公历日
     *
     * @return SolarDay 公历日
     */
    function getSolarDay(): SolarDay
    {
        return SolarDay::fromYmd($this->year, $this->month, $this->day);
    }

    function getName(): string
    {
        return sprintf('%02d:%02d:%02d', $this->hour, $this->minute, $this->second);
    }

    function __toString(): string
    {
        return sprintf('%s %s', $this->getSolarDay(), $this->getName());
    }

    /**
     * 是否在指定公历时刻之前
     *
     * @param SolarTime $target 公历时刻
     * @return bool true/false
     */
    function isBefore(SolarTime $target): bool
    {
        $aDay = $this->getSolarDay();
        $bDay = $target->getSolarDay();
        if (!$aDay->equals($bDay)) {
            return $aDay->isBefore($bDay);
        }
        if ($this->hour != $target->hour) {
            return $this->hour < $target->hour;
        }
        return $this->minute != $target->minute ? $this->minute < $target->minute : $this->second < $target->second;
    }

    /**
     * 是否在指定公历时刻之后
     *
     * @param SolarTime $target 公历时刻
     * @return true/false
     */
    function isAfter(SolarTime $target): bool
    {
        $aDay = $this->getSolarDay();
        $bDay = $target->getSolarDay();
        if (!$aDay->equals($bDay)) {
            return $aDay->isAfter($bDay);
        }
        if ($this->hour != $target->hour) {
            return $this->hour > $target->hour;
        }
        return $this->minute != $target->minute ? $this->minute > $target->minute : $this->second > $target->second;
    }

    /**
     * 节气
     *
     * @return SolarTerm 节气
     */
    function getTerm(): SolarTerm
    {
        $term = $this->getSolarDay()->getTerm();
        if ($this->isBefore($term->getJulianDay()->getSolarTime())) {
            $term = $term->next(-1);
        }
        return $term;
    }

    /**
     * 候
     *
     * @return Phenology 候
     */
    function getPhenology(): Phenology
    {
        $p = $this->getSolarDay()->getPhenology();
        if ($this->isBefore($p->getJulianDay()->getSolarTime())) {
            $p = $p->next(-1);
        }
        return $p;
    }

    /**
     * 儒略日
     *
     * @return JulianDay 儒略日
     */
    function getJulianDay(): JulianDay
    {
        return JulianDay::fromYmdHms($this->year, $this->month, $this->day, $this->hour, $this->minute, $this->second);
    }

    /**
     * 公历时刻相减，获得相差秒数
     *
     * @param SolarTime $target 公历时刻
     * @return int 秒数
     */
    function subtract(SolarTime $target): int
    {
        $days = $this->getSolarDay()->subtract($target->getSolarDay());
        $cs = $this->hour * 3600 + $this->minute * 60 + $this->second;
        $ts = $target->hour * 3600 + $target->minute * 60 + $target->second;
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
        if ($n === 0) {
            return static::fromYmdHms($this->year, $this->month, $this->day, $this->hour, $this->minute, $this->second);
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

        $d = $this->getSolarDay()->next($td);
        return static::fromYmdHms($d->getYear(), $d->getMonth(), $d->getDay(), $th, $tm, $ts);
    }

    /**
     * 农历时辰
     *
     * @return LunarHour 农历时辰
     */
    function getLunarHour(): LunarHour
    {
        $d = $this->getSolarDay()->getLunarDay();
        return LunarHour::fromYmdHms($d->getYear(), $d->getMonth(), $d->getDay(), $this->hour, $this->minute, $this->second);
    }

    /**
     * 干支时辰
     *
     * @return SixtyCycleHour 干支时辰
     */
    function getSixtyCycleHour(): SixtyCycleHour
    {
        return SixtyCycleHour::fromSolarTime($this);
    }

    /**
     * 月相
     *
     * @return Phase 月相
     */
    function getPhase(): Phase
    {
        $month = $this->getLunarHour()->getLunarDay()->getLunarMonth()->next(1);
        $p = Phase::fromIndex($month->getYear(), $month->getMonthWithLeap(), 0);
        while ($p->getSolarTime()->isAfter($this)) {
            $p = $p->next(-1);
        }
        return $p;
    }
}
