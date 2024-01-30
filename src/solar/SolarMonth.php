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
        $this->year = SolarYear::fromYear($year);
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException(sprintf('illegal solar month: %d', $month));
        }
        $this->month = $month;
    }

    static function fromYm(int $year, int $month): static
    {
        return new static($year, $month);
    }

    /**
     * 年
     * @return SolarYear 年
     */
    function getYear(): SolarYear
    {
        return $this->year;
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
        if (1582 == $this->year->getYear() && 10 == $this->month) {
            return 21;
        }
        $d = self::$DAYS[$this->getIndexInYear()];
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
        return SolarSeason::fromIndex($this->year->getYear(), intdiv($this->getIndexInYear(), 3));
    }

    /**
     * 周数
     *
     * @param int $start 起始星期，1234560分别代表星期一至星期天
     * @return int 周数
     */
    function getWeekCount(int $start): int
    {
        return (int)ceil(($this->indexOf(SolarDay::fromYmd($this->year->getYear(), $this->month, 1)->getWeek()->getIndex() - $start, null, 7) + $this->getDayCount()) / 7);
    }

    function getName(): string
    {
        return self::$NAMES[$this->getIndexInYear()];
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->year, $this->getName());
    }

    function next(int $n): SolarMonth
    {
        if ($n == 0) {
            return self::fromYm($this->year->getYear(), $this->month);
        }
        $m = $this->month + $n;
        $y = $this->year->getYear() + intdiv($m, 12);
        $m %= 12;
        if ($m < 1) {
            $m += 12;
            $y--;
        }
        return self::fromYm($y, $m);
    }

    /**
     * 获取本月的公历周列表
     *
     * @param int $start 星期几作为一周的开始，1234560分别代表星期一至星期天
     * @return SolarWeek[] 周列表
     */
    function getWeeks(int $start): array
    {
        $size = $this->getWeekCount($start);
        $y = $this->year->getYear();
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $l[] = SolarWeek::fromYm($y, $this->month, $i, $start);
        }
        return $l;
    }

    /**
     * 获取本月的公历日列表
     *
     * @return SolarDay[] 公历日列表
     */
    function getDays(): array
    {
        $size = $this->getDayCount();
        $y = $this->year->getYear();
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $l[] = SolarDay::fromYmd($y, $this->month, $i + 1);
        }
        return $l;
    }
}
