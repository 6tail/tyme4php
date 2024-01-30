<?php

namespace com\tyme\eightchar;


use com\tyme\AbstractCulture;
use com\tyme\culture\Duty;
use com\tyme\sixtycycle\EarthBranch;
use com\tyme\sixtycycle\HeavenStem;
use com\tyme\sixtycycle\SixtyCycle;

/**
 * 八字
 * @author 6tail
 * @package com\tyme\eightchar
 */
class EightChar extends AbstractCulture
{
    /**
     * @var SixtyCycle 年柱
     */
    protected SixtyCycle $year;

    /**
     * @var SixtyCycle 月柱
     */
    protected SixtyCycle $month;

    /**
     * @var SixtyCycle 日柱
     */
    protected SixtyCycle $day;

    /**
     * @var SixtyCycle 时柱
     */
    protected SixtyCycle $hour;

    function __construct(SixtyCycle $year, SixtyCycle $month, SixtyCycle $day, SixtyCycle $hour)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->hour = $hour;
    }

    /**
     * 年柱
     *
     * @return SixtyCycle 年柱
     */
    function getYear(): SixtyCycle
    {
        return $this->year;
    }

    /**
     * 月柱
     *
     * @return SixtyCycle 月柱
     */
    function getMonth(): SixtyCycle
    {
        return $this->month;
    }

    /**
     * 日柱
     *
     * @return SixtyCycle 日柱
     */
    function getDay(): SixtyCycle
    {
        return $this->day;
    }

    /**
     * 时柱
     *
     * @return SixtyCycle 时柱
     */
    function getHour(): SixtyCycle
    {
        return $this->hour;
    }

    /**
     * 胎元
     *
     * @return SixtyCycle 胎元
     */
    function getFetalOrigin(): SixtyCycle
    {
        return SixtyCycle::fromName(sprintf('%s%s', $this->month->getHeavenStem()->next(1)->getName(), $this->month->getEarthBranch()->next(3)->getName()));
    }

    /**
     * 胎息
     *
     * @return SixtyCycle 胎息
     */
    function getFetalBreath(): SixtyCycle
    {
        return SixtyCycle::fromName(sprintf('%s%s', $this->day->getHeavenStem()->next(5)->getName(), EarthBranch::fromIndex(13 - $this->day->getEarthBranch()->getIndex())->getName()));
    }

    /**
     * 命宫
     *
     * @return SixtyCycle 命宫
     */
    function getOwnSign(): SixtyCycle
    {
        $offset = $this->month->getEarthBranch()->next(-1)->getIndex() + $this->hour->getEarthBranch()->next(-1)->getIndex();
        $offset = ($offset >= 14 ? 26 : 14) - $offset;
        $offset -= 1;
        return SixtyCycle::fromName(sprintf('%s%s', HeavenStem::fromIndex(($this->year->getHeavenStem()->getIndex() + 1) * 2 + $offset)->getName(), EarthBranch::fromIndex(2 + $offset)->getName()));
    }

    /**
     * 身宫
     *
     * @return SixtyCycle 身宫
     */
    function getBodySign(): SixtyCycle
    {
        $offset = $this->month->getEarthBranch()->getIndex() + $this->hour->getEarthBranch()->getIndex();
        $offset %= 12;
        $offset -= 1;
        return SixtyCycle::fromName(sprintf('%s%s', HeavenStem::fromIndex(($this->year->getHeavenStem()->getIndex() + 1) * 2 + $offset)->getName(), EarthBranch::fromIndex(2 + $offset)->getName()));
    }

    /**
     * 建除十二值神
     *
     * @return Duty 建除十二值神
     */
    function getDuty(): Duty
    {
        return Duty::fromIndex($this->day->getEarthBranch()->getIndex() - $this->month->getEarthBranch()->getIndex());
    }

    function getName(): string
    {
        return sprintf('%s %s %s %s', $this->year, $this->month, $this->day, $this->hour);
    }

}
