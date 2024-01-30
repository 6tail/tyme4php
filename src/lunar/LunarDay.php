<?php

namespace com\tyme\lunar;


use com\tyme\AbstractTyme;
use com\tyme\culture\Direction;
use com\tyme\culture\Duty;
use com\tyme\culture\fetus\FetusDay;
use com\tyme\culture\Phase;
use com\tyme\culture\star\nine\NineStar;
use com\tyme\culture\star\twelve\TwelveStar;
use com\tyme\culture\star\twentyeight\TwentyEightStar;
use com\tyme\culture\Week;
use com\tyme\festival\LunarFestival;
use com\tyme\sixtycycle\EarthBranch;
use com\tyme\sixtycycle\HeavenStem;
use com\tyme\sixtycycle\SixtyCycle;
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
     * 月
     *
     * @return LunarMonth 月
     */
    function getMonth(): LunarMonth
    {
        return $this->month;
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
        if ($n == 0) {
            return self::fromYmd($this->month->getYear()->getYear(), $this->month->getMonthWithLeap(), $this->day);
        }
        $d = $this->day + $n;
        $lm = $this->month;
        $daysInMonth = $lm->getDayCount();
        $forward = $n > 0;
        $add = $forward ? 1 : -1;
        while ($forward ? ($d > $daysInMonth) : ($d <= 0)) {
            if ($forward) {
                $d -= $daysInMonth;
            }
            $lm = $lm->next($add);
            $daysInMonth = $lm->getDayCount();
            if (!$forward) {
                $d += $daysInMonth;
            }
        }
        return self::fromYmd($lm->getYear()->getYear(), $lm->getMonthWithLeap(), $d);
    }

    /**
     * 是否在指定农历日之前
     *
     * @param LunarDay target 农历日
     * @return bool true/false
     */
    function isBefore(LunarDay $target): bool
    {
        $aYear = $this->month->getYear()->getYear();
        $targetMonth = $target->getMonth();
        $bYear = $targetMonth->getYear()->getYear();
        if ($aYear == $bYear) {
            $aMonth = $this->month->getMonth();
            $bMonth = $targetMonth->getMonth();
            if ($aMonth == $bMonth) {
                if ($this->month->isLeap() && !$targetMonth->isLeap()) {
                    return false;
                }
                return $this->day < $target->getDay();
            }
            return $aMonth < $bMonth;
        }
        return $aYear < $bYear;
    }

    /**
     * 是否在指定农历日之后
     *
     * @param LunarDay target 农历日
     * @return bool true/false
     */
    function isAfter(LunarDay $target): bool
    {
        $aYear = $this->month->getYear()->getYear();
        $targetMonth = $target->getMonth();
        $bYear = $targetMonth->getYear()->getYear();
        if ($aYear == $bYear) {
            $aMonth = $this->month->getMonth();
            $bMonth = $targetMonth->getMonth();
            if ($aMonth == $bMonth) {
                if ($this->month->isLeap() && !$targetMonth->isLeap()) {
                    return true;
                }
                return $this->day > $target->getDay();
            }
            return $aMonth > $bMonth;
        }
        return $aYear > $bYear;
    }

    /**
     * 星期
     *
     * @return Week 星期
     */
    function getWeek(): Week
    {
        return $this->getSolarDay()->getJulianDay()->getWeek();
    }

    /**
     * 当天的年干支
     *
     * @return SixtyCycle 干支
     */
    function getYearSixtyCycle(): SixtyCycle
    {
        $solarDay = $this->getSolarDay();
        $solarYear = $solarDay->getMonth()->getYear()->getYear();
        $springSolarDay = SolarTerm::fromIndex($solarYear, 3)->getJulianDay()->getSolarDay();
        $lunarYear = $this->month->getYear();
        $year = $lunarYear->getYear();
        $sixtyCycle = $lunarYear->getSixtyCycle();
        if ($year == $solarYear) {
            if ($solarDay->isBefore($springSolarDay)) {
                $sixtyCycle = $sixtyCycle->next(-1);
            }
        } else if ($year < $solarYear) {
            if (!$solarDay->isBefore($springSolarDay)) {
                $sixtyCycle = $sixtyCycle->next(1);
            }
        }
        return $sixtyCycle;
    }

    /**
     * 当天的月干支
     *
     * @return SixtyCycle 干支
     */
    function getMonthSixtyCycle(): SixtyCycle
    {
        $solarDay = $this->getSolarDay();
        $year = $solarDay->getMonth()->getYear()->getYear();
        $term = $solarDay->getTerm();
        $index = $term->getIndex() - 3;
        if ($index < 0 && $term->getJulianDay()->getSolarDay()->isAfter(SolarTerm::fromIndex($year, 3)->getJulianDay()->getSolarDay())) {
            $index += 24;
        }
        return LunarMonth::fromYm($year, 1)->getSixtyCycle()->next((int)floor($index / 2));
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
     */
    function getDuty(): Duty
    {
        return Duty::fromIndex($this->getSixtyCycle()->getEarthBranch()->getIndex() - $this->getMonthSixtyCycle()->getEarthBranch()->getIndex());
    }

    /**
     * 黄道黑道十二神
     *
     * @return TwelveStar 黄道黑道十二神
     */
    function getTwelveStar(): TwelveStar
    {
        return TwelveStar::fromIndex($this->getSixtyCycle()->getEarthBranch()->getIndex() + (8 - $this->getMonthSixtyCycle()->getEarthBranch()->getIndex() % 6) * 2);
    }

    /**
     * 九星
     *
     * @return NineStar 九星
     */
    function getNineStar(): NineStar
    {
        $solar = $this->getSolarDay();
        $dongZhi = SolarTerm::fromIndex($solar->getMonth()->getYear()->getYear(), 0);
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
        if (!$solar->isBefore($solarShunBai) && $solar->isBefore($solarNiZi)) {
            $offset = $solar->subtract($solarShunBai);
        } else if (!$solar->isBefore($solarNiZi) && $solar->isBefore($solarShunBai2)) {
            $offset = 8 - $solar->subtract($solarNiZi);
        } else if (!$solar->isBefore($solarShunBai2)) {
            $offset = $solar->subtract($solarShunBai2);
        } else if ($solar->isBefore($solarShunBai)) {
            $offset = 8 + $solarShunBai->subtract($solar);
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
        if ($index % 12 < 6) {
            return Direction::fromIndex([2, 8, 4, 6, 0][intdiv($index, 12)]);
        }
        return $this->month->getYear()->getJupiterDirection();
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
        return $this->month->getFirstJulianDay()->next($this->day - 1)->getSolarDay();
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
        $m = $this->getMonth();
        return LunarFestival::fromYmd($m->getYear()->getYear(), $m->getMonthWithLeap(), $this->day);
    }

    function equals(mixed $o): bool
    {
        if (!($o instanceof LunarDay)) {
            return false;
        }
        return $this->month->equals($o->getMonth()) && $this->day == $o->getDay();
    }

}
