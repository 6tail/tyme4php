<?php

namespace com\tyme\sixtycycle;


use com\tyme\AbstractTyme;
use com\tyme\culture\star\nine\NineStar;
use com\tyme\culture\star\twelve\TwelveStar;
use com\tyme\culture\Taboo;
use com\tyme\eightchar\EightChar;
use com\tyme\lunar\LunarMonth;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;

/**
 * 干支时辰（立春换年，节令换月，23点换日）
 * @author 6tail
 * @package com\tyme\sixtycycle
 */
class SixtyCycleHour extends AbstractTyme
{
    /**
     * @var SolarTime 公历时刻
     */
    protected SolarTime $solarTime;

    /**
     * @var SixtyCycleDay 干支日
     */
    protected SixtyCycleDay $day;

    /**
     * @var SixtyCycle 时柱
     */
    protected SixtyCycle $hour;

    function __construct(SolarTime $solarTime)
    {
        $solarYear = $solarTime->getYear();
        $springSolarTime = SolarTerm::fromIndex($solarYear, 3)->getJulianDay()->getSolarTime();
        $lunarHour = $solarTime->getLunarHour();
        $lunarDay = $lunarHour->getLunarDay();
        $lunarYear = $lunarDay->getLunarMonth()->getLunarYear();
        if ($lunarYear->getYear() == $solarYear) {
            if ($solarTime->isBefore($springSolarTime)) {
                $lunarYear = $lunarYear->next(-1);
            }
        } else if ($lunarYear->getYear() < $solarYear) {
            if (!$solarTime->isBefore($springSolarTime)) {
                $lunarYear = $lunarYear->next(1);
            }
        }

        $term = $solarTime->getTerm();
        $index = $term->getIndex() - 3;
        if ($index < 0 && $term->getJulianDay()->getSolarTime()->isAfter(SolarTerm::fromIndex($solarYear, 3)->getJulianDay()->getSolarTime())) {
            $index += 24;
        }
        $d = $lunarDay->getSixtyCycle();
        $this->solarTime = $solarTime;
        $this->day = new SixtyCycleDay($solarTime->getSolarDay(), new SixtyCycleMonth(SixtyCycleYear::fromYear($lunarYear->getYear()), LunarMonth::fromYm($solarYear, 1)->getSixtyCycle()->next((int)floor($index * 0.5))), $solarTime->getHour() < 23 ? $d : $d->next(1));
        $this->hour = $lunarHour->getSixtyCycle();
    }

    static function fromSolarTime(SolarTime $solarTime): static
    {
        return new static($solarTime);
    }

    /**
     * 干支日
     *
     * @return SixtyCycleDay 干支日
     */
    function getSixtyCycleDay(): SixtyCycleDay
    {
        return $this->day;
    }

    /**
     * 年柱
     *
     * @return SixtyCycle 年柱
     */
    function getYear(): SixtyCycle
    {
        return $this->day->getYear();
    }

    /**
     * 月柱
     *
     * @return SixtyCycle 月柱
     */
    function getMonth(): SixtyCycle
    {
        return $this->day->getMonth();
    }

    /**
     * 日柱
     *
     * @return SixtyCycle 日柱
     */
    function getDay(): SixtyCycle
    {
        return $this->day->getSixtyCycle();
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        return $this->hour;
    }

    /**
     * 公历时刻
     *
     * @return SolarTime 公历时刻
     */
    function getSolarTime(): SolarTime
    {
        return $this->solarTime;
    }

    function getName(): string
    {
        return sprintf('%s时', $this->hour);
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->day, $this->getName());
    }

    function getIndexInDay(): int
    {
        $h = $this->solarTime->getHour();
        return $h == 23 ? 0 : intdiv($h + 1, 2);
    }

    function next(int $n): SixtyCycleHour
    {
        return static::fromSolarTime($this->solarTime->next($n));
    }

    /**
     * 黄道黑道十二神
     *
     * @return TwelveStar 黄道黑道十二神
     */
    function getTwelveStar(): TwelveStar
    {
        return TwelveStar::fromIndex($this->hour->getEarthBranch()->getIndex() + (8 - $this->getDay()->getEarthBranch()->getIndex() % 6) * 2);
    }

    /**
     * 九星（时家紫白星歌诀：三元时白最为佳，冬至阳生顺莫差，孟日七宫仲一白，季日四绿发萌芽，每把时辰起甲子，本时星耀照光华，时星移入中宫去，顺飞八方逐细查。夏至阴生逆回首，孟归三碧季加六，仲在九宫时起甲，依然掌中逆轮跨。）
     *
     * @return NineStar 九星
     */
    function getNineStar(): NineStar
    {
        $solar = $this->solarTime->getSolarDay();
        $dongZhi = SolarTerm::fromIndex($solar->getYear(), 0);
        $xiaZhi = $dongZhi->next(12);
        $earthBranchIndex = $this->getIndexInDay() % 12;
        $index = [8, 5, 2][$this->day->getSixtyCycle()->getEarthBranch()->getIndex() % 3];
        if (!$solar->isBefore($dongZhi->getJulianDay()->getSolarDay()) && $solar->isBefore($xiaZhi->getJulianDay()->getSolarDay())) {
            $index = 8 + $earthBranchIndex - $index;
        } else {
            $index -= $earthBranchIndex;
        }
        return NineStar::fromIndex($index);
    }

    /**
     * 八字
     *
     * @return EightChar 八字
     */
    function getEightChar(): EightChar
    {
        return new EightChar($this->getYear(), $this->getMonth(), $this->getDay(), $this->hour);
    }

    /**
     * 宜
     * @return Taboo[] 宜忌列表
     */
    function getRecommends(): array
    {
        return Taboo::getHourRecommends($this->getDay(), $this->hour);
    }

    /**
     * 忌
     * @return Taboo[] 宜忌列表
     */
    function getAvoids(): array
    {
        return Taboo::getHourAvoids($this->getDay(), $this->hour);
    }
}
