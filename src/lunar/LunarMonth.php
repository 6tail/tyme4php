<?php

namespace com\tyme\lunar;


use com\tyme\culture\Direction;
use com\tyme\culture\fetus\FetusMonth;
use com\tyme\culture\ren\MinorRen;
use com\tyme\culture\star\nine\NineStar;
use com\tyme\jd\JulianDay;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\solar\SolarTerm;
use com\tyme\unit\AbstractLeapMonth;
use com\tyme\unit\AbstractYear;
use com\tyme\util\ShouXingUtil;
use InvalidArgumentException;

/**
 * 农历月
 * @author 6tail
 * @package com\tyme\lunar
 */
class LunarMonth extends AbstractLeapMonth
{
    private static array $NEW_MOON_CACHE = [];
    static array $NAMES = ['正月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];

    protected function __construct(int $year, int $month)
    {
        self::validate($year, $month);
        parent::__construct($year, $month);
    }

    static function validate($year, $month): void
    {
        if ($month === 0 || $month > 12 || $month < -12) {
            throw new InvalidArgumentException('illegal lunar month: ' . $month);
        }
        if ($month < 0 && -$month != LunarYear::fromYear($year)->getLeapMonth()) {
            throw new InvalidArgumentException(sprintf('illegal leap month %d in lunar year %d', -$month, $year));
        }
    }

    static function fromYm(int $year, int $month): static
    {
        return new static($year, $month);
    }

    /**
     * 农历年
     *
     * @return LunarYear 农历年
     */
    function getLunarYear(): LunarYear
    {
        return LunarYear::fromYear($this->year);
    }

    function getAbstractYear(): AbstractYear
    {
        return $this->getLunarYear();
    }

    protected function getNewMoon(): float
    {
        $idx = $this->leap ? 12 : $this->month - 1;
        if (isset(self::$NEW_MOON_CACHE[$this->year][$idx])) {
            return self::$NEW_MOON_CACHE[$this->year][$idx];
        }

        $year = $this->year;
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

        // 本月初一
        $v =  $w + 29.5306 * ($offset + $this->getIndexInYear());
        self::$NEW_MOON_CACHE[$this->year][$idx] = $v;
        return $v;
    }

    function getDayCount(): int
    {
        // 大月30天，小月29天
        $w = $this->getNewMoon();
        // 本月天数 = 下月初一 - 本月初一
        return (int)(ShouXingUtil::calcShuo($w + 29.5306) - ShouXingUtil::calcShuo($w));
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
        return JulianDay::fromJulianDay(JulianDay::J2000 + ShouXingUtil::calcShuo($this->getNewMoon()));
    }

    /**
     * 依据国家标准《农历的编算和颁行》GB/T 33661-2017中农历月的命名方法。
     *
     * @return string 名称
     */
    function getName(): string
    {
        return ($this->leap ? '闰' : '') . static::$NAMES[$this->month - 1];
    }

    function next(int $n): LunarMonth
    {
        $m = parent::next($n);
        return static::fromYm($m->year, $m->getMonthValue());
    }

    /**
     * 本月的农历日列表
     *
     * @return LunarDay[] 农历日列表
     */
    function getDays(): array
    {
        $size = $this->getDayCount();
        $m = $this->getMonthValue();
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $l[] = LunarDay::fromYmd($this->year, $m, $i + 1);
        }
        return $l;
    }

    function getFirstDay(): LunarDay
    {
        return LunarDay::fromYmd($this->year, $this->getMonthValue(), 1);
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
        $m = $this->getMonthValue();
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $l[] = LunarWeek::fromYm($this->year, $m, $i, $start);
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
        return SixtyCycle::fromIndex($this->year * 12 + $this->month - 47);
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
        return NineStar::fromIndex(27 - $this->getLunarYear()->getSixtyCycle()->getEarthBranch()->getIndex() % 3 * 3 - $index);
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
