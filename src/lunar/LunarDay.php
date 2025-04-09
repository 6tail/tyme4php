<?php

namespace com\tyme\lunar;


use com\tyme\AbstractTyme;
use com\tyme\culture\Direction;
use com\tyme\culture\Duty;
use com\tyme\culture\Element;
use com\tyme\culture\fetus\FetusDay;
use com\tyme\culture\God;
use com\tyme\culture\Phase;
use com\tyme\culture\ren\MinorRen;
use com\tyme\culture\star\nine\NineStar;
use com\tyme\culture\star\six\SixStar;
use com\tyme\culture\star\twelve\TwelveStar;
use com\tyme\culture\star\twentyeight\TwentyEightStar;
use com\tyme\culture\Taboo;
use com\tyme\culture\Week;
use com\tyme\festival\LunarFestival;
use com\tyme\sixtycycle\EarthBranch;
use com\tyme\sixtycycle\HeavenStem;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\sixtycycle\SixtyCycleDay;
use com\tyme\solar\SolarDay;
use com\tyme\solar\SolarTerm;
use InvalidArgumentException;

/**
 * 农历日
 * @author 6tail
 * @package com\tyme\lunar
 */
class LunarDay extends AbstractTyme
{
    static array $NAMES = ['初一', '初二', '初三', '初四', '初五', '初六', '初七', '初八', '初九', '初十', '十一', '十二', '十三', '十四', '十五', '十六', '十七', '十八', '十九', '二十', '廿一', '廿二', '廿三', '廿四', '廿五', '廿六', '廿七', '廿八', '廿九', '三十'];

    /**
     * @var LunarMonth 农历月
     */
    protected LunarMonth $month;

    /**
     * @var int 日
     */
    protected int $day;

    /**
     * @var SolarDay|null 公历日（第一次使用时才会初始化）
     */
    protected ?SolarDay $solarDay = null;

    /**
     * @var SixtyCycleDay|null 干支日（第一次使用时才会初始化）
     */
    protected ?SixtyCycleDay $sixtyCycleDay = null;

    protected function __construct(int $year, int $month, int $day)
    {
        $m = LunarMonth::fromYm($year, $month);
        if ($day < 1 || $day > $m->getDayCount()) {
            throw new InvalidArgumentException(sprintf('illegal day %d in %s', $day, $m));
        }
        $this->month = $m;
        $this->day = $day;
    }

    static function fromYmd(int $year, int $month, int $day): static
    {
        return new static($year, $month, $day);
    }

    /**
     * 农历月
     *
     * @return LunarMonth 农历月
     */
    function getLunarMonth(): LunarMonth
    {
        return $this->month;
    }

    /**
     * 年
     *
     * @return int 年
     */
    function getYear(): int
    {
        return $this->month->getYear();
    }

    /**
     * 月
     *
     * @return int 月，闰月为负数
     */
    function getMonth(): int
    {
        return $this->month->getMonthWithLeap();
    }

    /**
     * 日
     *
     * @return int 日
     */
    function getDay(): int
    {
        return $this->day;
    }

    function getName(): string
    {
        return self::$NAMES[$this->day - 1];
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->month, $this->getName());
    }

    function next(int $n): LunarDay
    {
        return $this->getSolarDay()->next($n)->getLunarDay();
    }

    /**
     * 是否在指定农历日之前
     *
     * @param LunarDay $target 农历日
     * @return bool true/false
     */
    function isBefore(LunarDay $target): bool
    {
        $aYear = $this->getYear();
        $bYear = $target->getYear();
        if ($aYear != $bYear) {
            return $aYear < $bYear;
        }
        $aMonth = $this->getMonth();
        $bMonth = $target->getMonth();
        if ($aMonth != $bMonth) {
            return abs($aMonth) < abs($bMonth);
        }
        return $this->day < $target->getDay();
    }

    /**
     * 是否在指定农历日之后
     *
     * @param LunarDay $target 农历日
     * @return bool true/false
     */
    function isAfter(LunarDay $target): bool
    {
        $aYear = $this->getYear();
        $bYear = $target->getYear();
        if ($aYear != $bYear) {
            return $aYear > $bYear;
        }
        $aMonth = $this->getMonth();
        $bMonth = $target->getMonth();
        if ($aMonth != $bMonth) {
            return abs($aMonth) >= abs($bMonth);
        }
        return $this->day > $target->getDay();
    }

    /**
     * 星期
     *
     * @return Week 星期
     */
    function getWeek(): Week
    {
        return $this->getSolarDay()->getWeek();
    }

    /**
     * 当天的年干支
     *
     * @return SixtyCycle 干支
     * @deprecated
     * @see SixtyCycleDay
     */
    function getYearSixtyCycle(): SixtyCycle
    {
        return $this->getSixtyCycleDay()->getYear();
    }

    /**
     * 当天的月干支
     *
     * @return SixtyCycle 干支
     * @deprecated
     * @see SixtyCycleDay
     */
    function getMonthSixtyCycle(): SixtyCycle
    {
        return $this->getSixtyCycleDay()->getMonth();
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        $offset = (int)$this->month->getFirstJulianDay()->next($this->day - 12)->getDay();
        return SixtyCycle::fromName(sprintf('%s%s', HeavenStem::fromIndex($offset)->getName(), EarthBranch::fromIndex($offset)->getName()));
    }

    /**
     * 建除十二值神
     *
     * @return Duty 建除十二值神
     * @see SixtyCycleDay
     */
    function getDuty(): Duty
    {
        return $this->getSixtyCycleDay()->getDuty();
    }

    /**
     * 黄道黑道十二神
     *
     * @return TwelveStar 黄道黑道十二神
     * @see SixtyCycleDay
     */
    function getTwelveStar(): TwelveStar
    {
        return $this->getSixtyCycleDay()->getTwelveStar();
    }

    /**
     * 九星
     *
     * @return NineStar 九星
     */
    function getNineStar(): NineStar
    {
        $d = $this->getSolarDay();
        $dongZhi = SolarTerm::fromIndex($d->getYear(), 0);
        $xiaZhi = $dongZhi->next(12);
        $dongZhi2 = $dongZhi->next(24);
        $dongZhiSolar = $dongZhi->getJulianDay()->getSolarDay();
        $xiaZhiSolar = $xiaZhi->getJulianDay()->getSolarDay();
        $dongZhiSolar2 = $dongZhi2->getJulianDay()->getSolarDay();
        $dongZhiIndex = $dongZhiSolar->getLunarDay()->getSixtyCycle()->getIndex();
        $xiaZhiIndex = $xiaZhiSolar->getLunarDay()->getSixtyCycle()->getIndex();
        $dongZhiIndex2 = $dongZhiSolar2->getLunarDay()->getSixtyCycle()->getIndex();
        $solarShunBai = $dongZhiSolar->next($dongZhiIndex > 29 ? 60 - $dongZhiIndex : -$dongZhiIndex);
        $solarShunBai2 = $dongZhiSolar2->next($dongZhiIndex2 > 29 ? 60 - $dongZhiIndex2 : -$dongZhiIndex2);
        $solarNiZi = $xiaZhiSolar->next($xiaZhiIndex > 29 ? 60 - $xiaZhiIndex : -$xiaZhiIndex);
        $offset = 0;
        if (!$d->isBefore($solarShunBai) && $d->isBefore($solarNiZi)) {
            $offset = $d->subtract($solarShunBai);
        } else if (!$d->isBefore($solarNiZi) && $d->isBefore($solarShunBai2)) {
            $offset = 8 - $d->subtract($solarNiZi);
        } else if (!$d->isBefore($solarShunBai2)) {
            $offset = $d->subtract($solarShunBai2);
        } else if ($d->isBefore($solarShunBai)) {
            $offset = 8 + $solarShunBai->subtract($d);
        }
        return NineStar::fromIndex($offset);
    }

    /**
     * 太岁方位
     *
     * @return Direction 方位
     */
    function getJupiterDirection(): Direction
    {
        $index = $this->getSixtyCycle()->getIndex();
        return $index % 12 < 6 ? Element::fromIndex(intdiv($index, 12))->getDirection() : $this->month->getLunarYear()->getJupiterDirection();
    }

    /**
     * 逐日胎神
     *
     * @return FetusDay 逐日胎神
     */
    function getFetusDay(): FetusDay
    {
        return FetusDay::fromLunarDay($this);
    }

    /**
     * 月相
     *
     * @return Phase 月相
     */
    function getPhase(): Phase
    {
        return Phase::fromIndex($this->day - 1);
    }

    /**
     * 公历日
     *
     * @return SolarDay 公历日
     */
    function getSolarDay(): SolarDay
    {
        if ($this->solarDay == null)
        {
            $this->solarDay = $this->month->getFirstJulianDay()->next($this->day - 1)->getSolarDay();
        }
        return $this->solarDay;
    }

    /**
     * 干支日
     *
     * @return SixtyCycleDay 干支日
     */
    function getSixtyCycleDay(): SixtyCycleDay
    {
        if ($this->sixtyCycleDay == null)
        {
            $this->sixtyCycleDay = $this->getSolarDay()->getSixtyCycleDay();
        }
        return $this->sixtyCycleDay;
    }

    /**
     * 二十八宿
     *
     * @return TwentyEightStar 二十八宿
     */
    function getTwentyEightStar(): TwentyEightStar
    {
        return TwentyEightStar::fromIndex([10, 18, 26, 6, 14, 22, 2][$this->getSolarDay()->getWeek()->getIndex()])->next(-7 * $this->getSixtyCycle()->getEarthBranch()->getIndex());
    }

    /**
     * 农历传统节日，如果当天不是农历传统节日，返回null
     *
     * @return ?LunarFestival 农历传统节日
     */
    function getFestival(): ?LunarFestival
    {
        return LunarFestival::fromYmd($this->getYear(), $this->getMonth(), $this->day);
    }

    /**
     * 当天的时辰列表
     * @return LunarHour[] 时辰列表
     */
    function getHours(): array
    {
        $y = $this->getYear();
        $m = $this->getMonth();
        $l = array();
        $l[] = LunarHour::fromYmdHms($y, $m, $this->day, 0, 0, 0);
        for ($i = 0; $i < 24; $i += 2) {
            $l[] = LunarHour::fromYmdHms($y, $m, $this->day, $i + 1, 0, 0);
        }
        return $l;
    }

    /**
     * 神煞列表(吉神宜趋，凶神宜忌)
     * @return God[] 神煞列表
     * @see SixtyCycleDay
     */
    function getGods(): array
    {
        return $this->getSixtyCycleDay()->getGods();
    }

    /**
     * 宜
     * @return Taboo[] 宜忌列表
     * @see SixtyCycleDay
     */
    function getRecommends(): array
    {
        return $this->getSixtyCycleDay()->getRecommends();
    }

    /**
     * 忌
     * @return Taboo[] 宜忌列表
     * @see SixtyCycleDay
     */
    function getAvoids(): array
    {
        return $this->getSixtyCycleDay()->getAvoids();
    }

    /**
     * 六曜
     * @return SixStar 六曜
     */
    function getSixStar(): SixStar
    {
        return SixStar::fromIndex(($this->month->getMonth() + $this->day - 2) % 6);
    }

    /**
     * 小六壬
     * @return MinorRen 小六壬
     */
    function getMinorRen(): MinorRen
    {
        return $this->getLunarMonth()->getMinorRen()->next($this->day - 1);
    }
}
