<?php

namespace com\tyme\sixtycycle;


use com\tyme\AbstractTyme;
use com\tyme\culture\Direction;
use com\tyme\culture\Duty;
use com\tyme\culture\Element;
use com\tyme\culture\fetus\FetusDay;
use com\tyme\culture\God;
use com\tyme\culture\star\nine\NineStar;
use com\tyme\culture\star\twelve\TwelveStar;
use com\tyme\culture\star\twentyeight\TwentyEightStar;
use com\tyme\culture\Taboo;
use com\tyme\lunar\LunarMonth;
use com\tyme\solar\SolarDay;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;

/**
 * 干支日（立春换年，节令换月）
 *
 * @author 6tail
 * @package com\tyme\sixtycycle
 */
class SixtyCycleDay extends AbstractTyme
{
    /**
     * @var SolarDay 公历日
     */
    protected SolarDay $solarDay;

    /**
     * @var SixtyCycleMonth 干支月
     */
    protected SixtyCycleMonth $month;

    /**
     * @var SixtyCycle 日柱
     */
    protected SixtyCycle $day;

    function __construct(SolarDay $solarDay, SixtyCycleMonth $month, SixtyCycle $day)
    {
        $this->solarDay = $solarDay;
        $this->month = $month;
        $this->day = $day;
    }

    static function fromSolarDay(SolarDay $solarDay): static
    {
        $solarYear = $solarDay->getYear();
        $springSolarDay = SolarTerm::fromIndex($solarYear, 3)->getSolarDay();
        $lunarDay = $solarDay->getLunarDay();
        $lunarYear = $lunarDay->getLunarMonth()->getLunarYear();
        if ($lunarYear->getYear() == $solarYear) {
            if ($solarDay->isBefore($springSolarDay)) {
                $lunarYear = $lunarYear->next(-1);
            }
        } else if ($lunarYear->getYear() < $solarYear) {
            if (!$solarDay->isBefore($springSolarDay)) {
                $lunarYear = $lunarYear->next(1);
            }
        }
        $term = $solarDay->getTerm();
        $index = $term->getIndex() - 3;
        if ($index < 0 && $term->getSolarDay()->isAfter($springSolarDay)) {
            $index += 24;
        }
        return new static($solarDay, new SixtyCycleMonth(SixtyCycleYear::fromYear($lunarYear->getYear()), LunarMonth::fromYm($solarYear, 1)->getSixtyCycle()->next((int)floor($index * 0.5))), $lunarDay->getSixtyCycle());
    }

    /**
     * 公历日
     *
     * @return SolarDay 公历日
     */
    function getSolarDay(): SolarDay
    {
        return $this->solarDay;
    }

    /**
     * 干支月
     *
     * @return SixtyCycleMonth 干支月
     */
    function getSixtyCycleMonth(): SixtyCycleMonth
    {
        return $this->month;
    }

    /**
     * 年柱
     *
     * @return SixtyCycle 年柱
     */
    function getYear(): SixtyCycle
    {
        return $this->month->getYear();
    }

    /**
     * 月柱
     *
     * @return SixtyCycle 月柱
     */
    function getMonth(): SixtyCycle
    {
        return $this->month->getSixtyCycle();
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        return $this->day;
    }

    function getName(): string
    {
        return sprintf('%s日', $this->day);
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->month, $this->getName());
    }

    function next(int $n): SixtyCycleDay
    {
        return static::fromSolarDay($this->solarDay->next($n));
    }

    /**
     * 建除十二值神
     *
     * @return Duty 建除十二值神
     */
    function getDuty(): Duty
    {
        return Duty::fromIndex($this->day->getEarthBranch()->getIndex() - $this->getMonth()->getEarthBranch()->getIndex());
    }

    /**
     * 黄道黑道十二神
     *
     * @return TwelveStar 黄道黑道十二神
     */
    function getTwelveStar(): TwelveStar
    {
        return TwelveStar::fromIndex($this->day->getEarthBranch()->getIndex() + (8 - $this->getMonth()->getEarthBranch()->getIndex() % 6) * 2);
    }

    /**
     * 九星
     *
     * @return NineStar 九星
     */
    function getNineStar(): NineStar
    {
        $dongZhi = SolarTerm::fromIndex($this->solarDay->getYear(), 0);
        $dongZhiSolar = $dongZhi->getSolarDay();
        $xiaZhiSolar = $dongZhi->next(12)->getSolarDay();
        $dongZhiSolar2 = $dongZhi->next(24)->getSolarDay();
        $dongZhiIndex = $dongZhiSolar->getLunarDay()->getSixtyCycle()->getIndex();
        $xiaZhiIndex = $xiaZhiSolar->getLunarDay()->getSixtyCycle()->getIndex();
        $dongZhiIndex2 = $dongZhiSolar2->getLunarDay()->getSixtyCycle()->getIndex();
        $solarShunBai = $dongZhiSolar->next($dongZhiIndex > 29 ? 60 - $dongZhiIndex : -$dongZhiIndex);
        $solarShunBai2 = $dongZhiSolar2->next($dongZhiIndex2 > 29 ? 60 - $dongZhiIndex2 : -$dongZhiIndex2);
        $solarNiZi = $xiaZhiSolar->next($xiaZhiIndex > 29 ? 60 - $xiaZhiIndex : -$xiaZhiIndex);
        $offset = 0;
        if (!$this->solarDay->isBefore($solarShunBai) && $this->solarDay->isBefore($solarNiZi)) {
            $offset = $this->solarDay->subtract($solarShunBai);
        } else if (!$this->solarDay->isBefore($solarNiZi) && $this->solarDay->isBefore($solarShunBai2)) {
            $offset = 8 - $this->solarDay->subtract($solarNiZi);
        } else if (!$this->solarDay->isBefore($solarShunBai2)) {
            $offset = $this->solarDay->subtract($solarShunBai2);
        } else if ($this->solarDay->isBefore($solarShunBai)) {
            $offset = 8 + $solarShunBai->subtract($this->solarDay);
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
        $index = $this->day->getIndex();
        return $index % 12 < 6 ? Element::fromIndex(intdiv($index, 12))->getDirection() : $this->month->getSixtyCycleYear()->getJupiterDirection();
    }

    /**
     * 逐日胎神
     *
     * @return FetusDay 逐日胎神
     */
    function getFetusDay(): FetusDay
    {
        return FetusDay::fromSixtyCycleDay($this);
    }

    /**
     * 二十八宿
     *
     * @return TwentyEightStar 二十八宿
     */
    function getTwentyEightStar(): TwentyEightStar
    {
        return TwentyEightStar::fromIndex([10, 18, 26, 6, 14, 22, 2][$this->solarDay->getWeek()->getIndex()])->next(-7 * $this->day->getEarthBranch()->getIndex());
    }

    /**
     * 干支时辰列表
     *
     * @return SixtyCycleHour[] 干支时辰列表
     */
    function getHours(): array
    {
        $l = array();
        $d = $this->solarDay->next(-1);
        $h = SixtyCycleHour::fromSolarTime(SolarTime::fromYmdHms($d->getYear(), $d->getMonth(), $d->getDay(), 23, 0, 0));
        $l[] = $h;
        for ($i = 0; $i < 11; $i++) {
            $h = $h->next(7200);
            $l[] = $h;
        }
        return $l;
    }

    /**
     * 神煞列表(吉神宜趋，凶神宜忌)
     *
     * @return God[] 神煞列表
     */
    function getGods(): array
    {
        return God::getDayGods($this->getMonth(), $this->day);
    }

    /**
     * 宜
     *
     * @return Taboo[] 宜忌列表
     */
    function getRecommends(): array
    {
        return Taboo::getDayRecommends($this->getMonth(), $this->day);
    }

    /**
     * 忌
     *
     * @return Taboo[] 宜忌列表
     */
    function getAvoids(): array
    {
        return Taboo::getDayAvoids($this->getMonth(), $this->day);
    }

    /**
     * 三柱
     *
     * @return ThreePillars 三柱
     */
    function getThreePillars(): ThreePillars
    {
        return new ThreePillars($this->getYear(), $this->getMonth(), $this->getSixtyCycle());
    }
}
