<?php

namespace com\tyme\rabbyung;

use com\tyme\solar\SolarDay;
use com\tyme\unit\DayUnit;
use InvalidArgumentException;

/**
 * 藏历日，仅支持藏历1950年十二月初一（公历1951年1月8日）至藏历2050年十二月三十（公历2051年2月11日）
 * @author 6tail
 * @package com\tyme\rabbyung
 */
class RabByungDay extends DayUnit
{
    static array $NAMES = ['初一', '初二', '初三', '初四', '初五', '初六', '初七', '初八', '初九', '初十', '十一', '十二', '十三', '十四', '十五', '十六', '十七', '十八', '十九', '二十', '廿一', '廿二', '廿三', '廿四', '廿五', '廿六', '廿七', '廿八', '廿九', '三十'];

    /**
     * @var bool 是否闰日
     */
    protected bool $leap;

    /**
     * 初始化
     *
     * @param int $year 藏历年
     * @param int $month 藏历月，闰月为负
     * @param int $day 藏历日，闰日为负
     */
    function __construct(int $year, int $month, int $day)
    {

        self::validate($year, $month, $day);
        parent::__construct($year, $month, abs($day));
        $this->leap = $day < 0;
    }

    static function validate(int $year, int $month, int $day): void
    {
        if ($day == 0 || $day < -30 || $day > 30) {
            throw new InvalidArgumentException(sprintf('illegal day %d in %s', $day, $month));
        }
        $m = RabByungMonth::fromYm($year, $month);
        $leap = $day < 0;
        $d = abs($day);
        if ($leap && !in_array($d, $m->getLeapDays())) {
            throw new InvalidArgumentException(sprintf('illegal leap day %d in %s', $d, $m));
        }
        if (!$leap && in_array($d, $m->getMissDays())) {
            throw new InvalidArgumentException(sprintf('illegal day %d in %s', $d, $m));
        }
    }

    /**
     * 初始化
     *
     * @param int $year 藏历年
     * @param int $month 藏历月，闰月为负
     * @param int $day 藏历日，闰日为负
     * @return static 藏历日
     */
    static function fromYmd(int $year, int $month, int $day): static
    {
        return new static($year, $month, $day);
    }

    /**
     * 初始化
     *
     * @param SolarDay $solarDay 公历日
     * @return static 藏历日
     */
    static function fromSolarDay(SolarDay $solarDay): static
    {
        $baseDay = SolarDay::fromYmd(1951, 1, 8);
        $days = $solarDay->subtract($baseDay);
        $m = RabByungMonth::fromYm(1950, 12);
        $count = $m->getDayCount();
        while ($days >= $count) {
            $days -= $count;
            $m = $m->next(1);
            $count = $m->getDayCount();
        }
        $day = $days + 1;
        foreach ($m->getSpecialDays() as $d) {
            if ($d < 0) {
                if ($day >= -$d) {
                    $day++;
                }
            } else {
                if ($day == $d + 1) {
                    $day = -$d;
                    break;
                } elseif ($day > $d + 1) {
                    $day--;
                }
            }
        }
        return new self($m->getYear(), $m->getMonthWithLeap(), $day);
    }

    /**
     * 藏历月
     *
     * @return RabByungMonth 藏历月
     */
    function getRabByungMonth(): RabByungMonth
    {
        return RabByungMonth::fromYm($this->year, $this->month);
    }

    /**
     * 是否闰日
     *
     * @return bool true/false
     */
    function isLeap(): bool
    {
        return $this->leap;
    }

    /**
     * 藏历日，闰日为负
     *
     * @return int 藏历日
     */
    function getDayWithLeap(): int
    {
        return $this->leap ? -$this->day : $this->day;
    }

    function getName(): string
    {
        return ($this->leap ? '闰' : '') . static::$NAMES[$this->day - 1];
    }

    function __toString(): string
    {
        return $this->getRabByungMonth() . $this->getName();
    }

    /**
     * 藏历日相减
     *
     * @param RabByungDay $target 藏历日
     * @return int 相差天数
     */
    function subtract(RabByungDay $target): int
    {
        return $this->getSolarDay()->subtract($target->getSolarDay());
    }

    /**
     * 公历日
     *
     * @return SolarDay 公历日
     */
    function getSolarDay(): SolarDay
    {
        $m = RabByungMonth::fromYm(1950, 12);
        $cm = $this->getRabByungMonth();
        $n = 0;
        while (!$cm->equals($m)) {
            $n += $m->getDayCount();
            $m = $m->next(1);
        }
        $t = $this->day;
        foreach ($m->getSpecialDays() as $d) {
            if ($d < 0) {
                if ($t > -$d) {
                    $t--;
                }
            } else {
                if ($t > $d) {
                    $t++;
                }
            }
        }
        if ($this->leap) {
            $t++;
        }
        return SolarDay::fromYmd(1951, 1, 7)->next($n + $t);
    }

    function next($n): static
    {
        return $this->getSolarDay()->next($n)->getRabByungDay();
    }
}
