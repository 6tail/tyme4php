<?php

namespace com\tyme\lunar;


use com\tyme\AbstractTyme;
use com\tyme\culture\Direction;
use com\tyme\culture\fetus\FetusMonth;
use com\tyme\culture\ren\MinorRen;
use com\tyme\culture\star\nine\NineStar;
use com\tyme\jd\JulianDay;
use com\tyme\sixtycycle\EarthBranch;
use com\tyme\sixtycycle\HeavenStem;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\solar\SolarTerm;
use com\tyme\util\ShouXingUtil;
use InvalidArgumentException;

/**
 * 农历月
 * @author 6tail
 * @package com\tyme\lunar
 */
class LunarMonth extends AbstractTyme
{
    /**
     * @var array 缓存
     */
    private static array $cache = array();

    static array $NAMES = ['正月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];


    /**
     * @var LunarYear 农历年
     */
    protected LunarYear $year;

    /**
     * @var int 月
     */
    protected int $month;

    /**
     * @var bool 是否闰月
     */
    protected bool $leap;

    /**
     * @var int 天数
     */
    protected int $dayCount;

    /**
     * @var int 位于当年的索引，0-12
     */
    protected int $indexInYear;

    /**
     * @var JulianDay 初一的儒略日
     */
    protected JulianDay $firstJulianDay;

    protected function __construct(int $year, int $month, ?array $cache = null)
    {
        if ($cache !== null) {
            $m = (int)$cache[1];
            $this->year = LunarYear::fromYear((int)$cache[0]);
            $this->month = abs($m);
            $this->leap = $m < 0;
            $this->dayCount = (int)$cache[2];
            $this->indexInYear = (int)$cache[3];
            $this->firstJulianDay = JulianDay::fromJulianDay((double)$cache[4]);
        } else {
            $currentYear = LunarYear::fromYear($year);
            $currentLeapMonth = $currentYear->getLeapMonth();
            if ($month == 0 || $month > 12 || $month < -12) {
                throw new InvalidArgumentException(sprintf('illegal lunar month: %d', $month));
            }
            $leap = $month < 0;
            $m = abs($month);
            if ($leap && $m != $currentLeapMonth) {
                throw new InvalidArgumentException(sprintf('illegal leap month %d in lunar year %d', $m, $year));
            }

            // 冬至
            $dongZhiJd = SolarTerm::fromIndex($year, 0)->getCursoryJulianDay();

            // 冬至前的初一，今年首朔的日月黄经差
            $w = ShouXingUtil::calcShuo($dongZhiJd);
            if ($w > $dongZhiJd) {
                $w -= 29.53;
            }

            // 正常情况正月初一为第3个朔日，但有些特殊的
            $offset = 2;
            if ($year > 8 && $year < 24) {
                $offset = 1;
            } else if (LunarYear::fromYear($year - 1)->getLeapMonth() > 10 && $year != 239 && $year != 240) {
                $offset = 3;
            }

            // 位于当年的索引
            $index = $m - 1;
            if ($leap || ($currentLeapMonth > 0 && $m > $currentLeapMonth)) {
                $index += 1;
            }
            $this->indexInYear = $index;

            // 本月初一
            $w += 29.5306 * ($offset + $index);
            $firstDay = ShouXingUtil::calcShuo($w);
            $this->firstJulianDay = JulianDay::fromJulianDay(JulianDay::J2000 + $firstDay);
            // 本月天数 = 下月初一 - 本月初一
            $this->dayCount = (int)(ShouXingUtil::calcShuo($w + 29.5306) - $firstDay);
            $this->year = $currentYear;
            $this->month = $m;
            $this->leap = $leap;
        }
    }

    static function fromYm(int $year, int $month): static
    {
        $c = null;
        $key = sprintf('%d%d', $year, $month);
        if (!empty(static::$cache[$key])) {
            $c = static::$cache[$key];
        }
        if (null != $c) {
            $m = new static(0, 0, $c);
        } else {
            $m = new static($year, $month);
            static::$cache[$key] = [
                $m->getYear(),
                $m->getMonthWithLeap(),
                $m->getDayCount(),
                $m->getIndexInYear(),
                $m->getFirstJulianDay()->getDay()
            ];
        }
        return $m;
    }

    /**
     * 农历年
     *
     * @return LunarYear 农历年
     */
    function getLunarYear(): LunarYear
    {
        return $this->year;
    }

    /**
     * 年
     *
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
     * 月
     *
     * @return int 月，当月为闰月时，返回负数
     */
    function getMonthWithLeap(): int
    {
        return $this->leap ? -$this->month : $this->month;
    }

    /**
     * 天数(大月30天，小月29天)
     *
     * @return int 天数
     */
    function getDayCount(): int
    {
        return $this->dayCount;
    }

    /**
     * 位于当年的索引(0-12)
     *
     * @return int 索引
     */
    function getIndexInYear(): int
    {
        return $this->indexInYear;
    }

    /**
     * 农历季节
     *
     * @return LunarSeason 农历季节
     */
    function getSeason(): LunarSeason
    {
        return LunarSeason::fromIndex($this->month - 1);
    }

    /**
     * 初一的儒略日
     *
     * @return JulianDay 儒略日
     */
    function getFirstJulianDay(): JulianDay
    {
        return $this->firstJulianDay;
    }

    /**
     * 是否闰月
     *
     * @return bool true/false
     */
    function isLeap(): bool
    {
        return $this->leap;
    }

    /**
     * 周数
     *
     * @param int $start 起始星期，1234560分别代表星期一至星期天
     * @return int 周数
     */
    function getWeekCount(int $start): int
    {
        return (int)ceil(($this->indexOf($this->firstJulianDay->getWeek()->getIndex() - $start, null, 7) + $this->getDayCount()) / 7);
    }

    /**
     * 依据国家标准《农历的编算和颁行》GB/T 33661-2017中农历月的命名方法。
     *
     * @return string 名称
     */
    function getName(): string
    {
        return sprintf('%s%s', $this->leap ? '闰' : '', static::$NAMES[$this->month - 1]);
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->year, $this->getName());
    }

    function next(int $n): LunarMonth
    {
        if ($n == 0) {
            return static::fromYm($this->getYear(), $this->getMonthWithLeap());
        }
        $m = $this->indexInYear + 1 + $n;
        $y = $this->year;
        if ($n > 0) {
            $monthCount = $y->getMonthCount();
            while ($m > $monthCount) {
                $m -= $monthCount;
                $y = $y->next(1);
                $monthCount = $y->getMonthCount();
            }
        } else {
            while ($m <= 0) {
                $y = $y->next(-1);
                $m += $y->getMonthCount();
            }
        }
        $leap = false;
        $leapMonth = $y->getLeapMonth();
        if ($leapMonth > 0) {
            if ($m == $leapMonth + 1) {
                $leap = true;
            }
            if ($m > $leapMonth) {
                $m--;
            }
        }
        return static::fromYm($y->getYear(), $leap ? -$m : $m);
    }

    /**
     * 本月的农历日列表
     *
     * @return LunarDay[] 农历日列表
     */
    function getDays(): array
    {
        $size = $this->getDayCount();
        $y = $this->getYear();
        $m = $this->getMonthWithLeap();
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $l[] = LunarDay::fromYmd($y, $m, $i + 1);
        }
        return $l;
    }

    /**
     * 本月的农历周列表
     *
     * @param int $start 星期几作为一周的开始，1234560分别代表星期一至星期天
     * @return LunarWeek[] 周列表
     */
    function getWeeks(int $start): array
    {
        $size = $this->getWeekCount($start);
        $y = $this->getYear();
        $m = $this->getMonthWithLeap();
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $l[] = LunarWeek::fromYm($y, $m, $i, $start);
        }
        return $l;
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        return SixtyCycle::fromName(sprintf('%s%s', HeavenStem::fromIndex($this->year->getSixtyCycle()->getHeavenStem()->getIndex() * 2 + $this->month + 1)->getName(), EarthBranch::fromIndex($this->month + 1)->getName()));
    }

    /**
     * 九星
     *
     * @return NineStar 九星
     */
    function getNineStar(): NineStar
    {
        $index = $this->getSixtyCycle()->getEarthBranch()->getIndex();
        if ($index < 2) {
            $index += 3;
        }
        return NineStar::fromIndex(27 - $this->year->getSixtyCycle()->getEarthBranch()->getIndex() % 3 * 3 - $index);
    }

    /**
     * 太岁方位
     *
     * @return Direction 方位
     */
    function getJupiterDirection(): Direction
    {
        $sixtyCycle = $this->getSixtyCycle();
        $n = [7, -1, 1, 3][$sixtyCycle->getEarthBranch()->next(-2)->getIndex() % 4];
        return $n != -1 ? Direction::fromIndex($n) : $sixtyCycle->getHeavenStem()->getDirection();
    }

    /**
     * 逐月胎神
     *
     * @return FetusMonth 逐月胎神
     */
    function getFetus(): FetusMonth
    {
        return FetusMonth::fromLunarMonth($this);
    }

    /**
     * 小六壬
     * @return MinorRen 小六壬
     */
    function getMinorRen(): MinorRen
    {
        return MinorRen::fromIndex(($this->month - 1) % 6);
    }
}
