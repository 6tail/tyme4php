<?php

namespace com\tyme\sixtycycle;


use com\tyme\AbstractTyme;
use com\tyme\culture\Direction;
use com\tyme\culture\star\nine\NineStar;
use com\tyme\solar\SolarTerm;

/**
 * 干支月
 * @author 6tail
 * @package com\tyme\sixtycycle
 */
class SixtyCycleMonth extends AbstractTyme
{
    /**
     * @var SixtyCycleYear 干支年
     */
    protected SixtyCycleYear $year;

    /**
     * @var SixtyCycle 月柱
     */
    protected SixtyCycle $month;

    function __construct(SixtyCycleYear $year, SixtyCycle $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    static function fromIndex(int $year, int $index): static
    {
        return SixtyCycleYear::fromYear($year)->getFirstMonth()->next($index);
    }

    /**
     * 干支年
     *
     * @return SixtyCycleYear 干支年
     */
    function getSixtyCycleYear(): SixtyCycleYear
    {
        return $this->year;
    }

    /**
     * 年柱
     *
     * @return SixtyCycle 年柱
     */
    function getYear(): SixtyCycle
    {
        return $this->year->getSixtyCycle();
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        return $this->month;
    }

    /**
     * 位于当年的索引(0-11)，寅月为0，依次类推
     *
     * @return int 索引
     */
    function getIndexInYear(): int
    {
        return $this->month->getEarthBranch()->next(-2)->getIndex();
    }

    /**
     * 名称
     *
     * @return string 名称
     */
    function getName(): string
    {
        return sprintf('%s月', $this->month);
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->year, $this->getName());
    }

    function next(int $n): SixtyCycleMonth
    {
        return new SixtyCycleMonth(SixtyCycleYear::fromYear((int)floor(($this->year->getYear() * 12 + $this->getIndexInYear() + $n) / 12)), $this->month->next($n));
    }

    /**
     * 首日（节令当天）
     *
     * @return SixtyCycleDay 干支日
     */
    function getFirstDay(): SixtyCycleDay
    {
        return SixtyCycleDay::fromSolarDay(SolarTerm::fromIndex($this->year->getYear(), 3 + $this->getIndexInYear() * 2)->getJulianDay()->getSolarDay());
    }

    /**
     * 本月的农历日列表
     *
     * @return SixtyCycleDay[] 农历日列表
     */
    function getDays(): array
    {
        $l = array();
        $d = $this->getFirstDay();
        while ($d->getSixtyCycleMonth()->equals($this)) {
            $l[] = $d;
            $d = $d->next(1);
        }
        return $l;
    }

    /**
     * 九星
     *
     * @return NineStar 九星
     */
    function getNineStar(): NineStar
    {
        $index = $this->month->getEarthBranch()->getIndex();
        if ($index < 2) {
            $index += 3;
        }
        return NineStar::fromIndex(27 - $this->getYear()->getEarthBranch()->getIndex() % 3 * 3 - $index);
    }

    /**
     * 太岁方位
     *
     * @return Direction 方位
     */
    function getJupiterDirection(): Direction
    {
        $n = [7, -1, 1, 3][$this->month->getEarthBranch()->next(-2)->getIndex() % 4];
        return $n == -1 ? $this->month->getHeavenStem()->getDirection() : Direction::fromIndex($n);
    }
}
