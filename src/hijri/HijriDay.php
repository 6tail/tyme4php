<?php

namespace com\tyme\hijri;

use com\tyme\jd\JulianDay;
use com\tyme\solar\SolarDay;
use com\tyme\unit\DayUnit;
use InvalidArgumentException;

/**
 * 回历日
 * @author 6tail
 * @package com\tyme\hijri
 */
class HijriDay extends DayUnit
{

    static array $NAMES = ['1日', '2日', '3日', '4日', '5日', '6日', '7日', '8日', '9日', '10日', '11日', '12日', '13日', '14日', '15日', '16日', '17日', '18日', '19日', '20日', '21日', '22日', '23日', '24日', '25日', '26日', '27日', '28日', '29日', '30日'];

    protected function __construct(int $year, int $month, int $day)
    {
        self::validate($year, $month, $day);
        parent::__construct($year, $month, $day);
    }

    static function validate(int $year, int $month, int $day): void
    {
        if ($day < 1 || $day > HijriMonth::fromYm($year, $month)->getDayCount()) {
            throw new InvalidArgumentException('illegal hijri day: {$year}-{$month}-{$day}');
        }
    }

    static function fromYmd(int $year, int $month, int $day): static
    {
        return new static($year, $month, $day);
    }

    /**
     * 回历月
     * @return HijriMonth 回历月
     */
    function getHijriMonth(): HijriMonth
    {
        return HijriMonth::fromYm($this->year, $this->month);
    }

    function getName(): string
    {
        return static::$NAMES[$this->day - 1];
    }

    function __toString(): string
    {
        return $this->getHijriMonth() . $this->getName();
    }

    function next(int $n): static
    {
        return $this->getSolarDay()->next($n)->getHijriDay();
    }

    /**
     * 是否在指定回历日之前
     * @param HijriDay $target 回历日
     * @return bool true/false
     */
    function isBefore(HijriDay $target): bool
    {
        return $this->getCompareIndex() < $target->getCompareIndex();
    }

    /**
     * 是否在指定回历日之后
     * @param HijriDay $target 回历日
     * @return bool true/false
     */
    function isAfter(HijriDay $target): bool
    {
        return $this->getCompareIndex() > $target->getCompareIndex();
    }

    /**
     * 位于当年的索引
     * @return int 索引
     */
    function getIndexInYear(): int
    {
        return $this->subtract(static::fromYmd($this->year, 1, 1));
    }

    /**
     * 回历日期相减，获得相差天数
     * @param HijriDay $target 回历日
     * @return int 天数
     */
    function subtract(HijriDay $target): int
    {
        return $this->getJulianDay()->subtract($target->getJulianDay());
    }

    /**
     * 儒略日
     * @return JulianDay 儒略日
     */
    function getJulianDay(): JulianDay
    {
        return JulianDay::fromJulianDay((int)floor((11 * $this->year + 3) / 30) + 354 * $this->year + 30 * $this->month - (int)floor(($this->month - 1) / 2) + $this->day + 1948055);
    }

    /**
     * 公历日
     * @return SolarDay 公历日
     */
    function getSolarDay(): SolarDay
    {
        return SolarDay::fromYmd(622, 7, 16)->next($this->subtract(HijriDay::fromYmd(1, 1, 1)));
    }
}
