<?php

namespace com\tyme\solar;


use com\tyme\AbstractTyme;
use InvalidArgumentException;

/**
 * 公历月
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarMonth extends AbstractTyme
{
    static array $NAMES = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];

    /**
     * @var int[] 每月天数
     */
    static array $DAYS = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    /**
     * @var SolarYear 年
     */
    protected SolarYear $year;

    /**
     * @var int 月
     */
    protected int $month;

    protected function __construct(int $year, int $month)
    {
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException(sprintf('illegal solar month: %d', $month));
        }
        $this->year = SolarYear::fromYear($year);
        $this->month = $month;
    }

    static function fromYm(int $year, int $month): static
    {
        return new static($year, $month);
    }

    /**
     * 公历年
     * @return SolarYear 公历年
     */
    function getSolarYear(): SolarYear
    {
        return $this->year;
    }

    /**
     * 年
     * @return int 年
     */
    function getYear(): int
    {
        return $this->year->getYear();
    }

    /**
     * 月
     *
     * @return int 月
     */
    function getMonth(): int
    {
        return $this->month;
    }

    /**
     * 天数（1582年10月只有21天)
     *
     * @return int 天数
     */
    function getDayCount(): int
    {
        if (1582 == $this->getYear() && 10 == $this->month) {
            return 21;
        }
        $d = static::$DAYS[$this->getIndexInYear()];
        //公历闰年2月多一天
        if (2 == $this->month && $this->year->isLeap()) {
            $d++;
        }
        return $d;
    }

    /**
     * 位于当年的索引(0-11)
     *
     * @return int 索引
     */
    function getIndexInYear(): int
    {
        return $this->month - 1;
    }

    /**
     * 公历季度
     *
     * @return SolarSeason 公历季度
     */
    function getSeason(): SolarSeason
    {
        return SolarSeason::fromIndex($this->getYear(), intdiv($this->getIndexInYear(), 3));
    }

    /**
     * 周数
     *
     * @param int $start 起始星期，1234560分别代表星期一至星期天
     * @return int 周数
     */
    function getWeekCount(int $start): int
    {
        return (int)ceil(($this->indexOf(SolarDay::fromYmd($this->getYear(), $this->month, 1)->getWeek()->getIndex() - $start, null, 7) + $this->getDayCount()) / 7);
    }

    function getName(): string
    {
        return static::$NAMES[$this->getIndexInYear()];
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->year, $this->getName());
    }

    function next(int $n): SolarMonth
    {
        $i = $this->month - 1 + $n;
        return static::fromYm(intdiv($this->getYear() * 12 + $i, 12), $this->indexOf($i, null, 12) + 1);
    }

    /**
     * 本月的公历周列表
     *
     * @param int $start 星期几作为一周的开始，1234560分别代表星期一至星期天
     * @return SolarWeek[] 周列表
     */
    function getWeeks(int $start): array
    {
        $size = $this->getWeekCount($start);
        $y = $this->getYear();
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $l[] = SolarWeek::fromYm($y, $this->month, $i, $start);
        }
        return $l;
    }

    /**
     * 本月的公历日列表
     *
     * @return SolarDay[] 公历日列表
     */
    function getDays(): array
    {
        $size = $this->getDayCount();
        $y = $this->getYear();
        $l = array();
        for ($i = 1; $i <= $size; $i++) {
            $l[] = SolarDay::fromYmd($y, $this->month, $i);
        }
        return $l;
    }
}
