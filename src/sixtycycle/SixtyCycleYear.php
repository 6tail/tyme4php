<?php

namespace com\tyme\sixtycycle;


use com\tyme\AbstractTyme;
use com\tyme\culture\Direction;
use com\tyme\culture\star\nine\NineStar;
use com\tyme\culture\Twenty;
use InvalidArgumentException;

/**
 * 干支年
 * @author 6tail
 * @package com\tyme\sixtycycle
 */
class SixtyCycleYear extends AbstractTyme
{
    /**
     * @var int 年
     */
    protected int $year;

    function __construct(int $year)
    {
        if ($year < -1 || $year > 9999) {
            throw new InvalidArgumentException(sprintf('illegal sixty cycle year: %d', $year));
        }
        $this->year = $year;
    }

    static function fromYear(int $year): static
    {
        return new static($year);
    }

    /**
     * 年
     *
     * @return int 年
     */
    function getYear(): int
    {
        return $this->year;
    }

    /**
     * 名称
     *
     * @return string 名称
     */
    function getName(): string
    {
        return sprintf('%s年', $this->getSixtyCycle());
    }

    function next(int $n): SixtyCycleYear
    {
        return static::fromYear($this->year + $n);
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        return SixtyCycle::fromIndex($this->year - 4);
    }

    /**
     * 运
     *
     * @return Twenty 运
     */
    function getTwenty(): Twenty
    {
        return Twenty::fromIndex((int)floor(($this->year - 1864) / 20));
    }

    /**
     * 九星
     *
     * @return NineStar 九星
     */
    function getNineStar(): NineStar
    {
        return NineStar::fromIndex(63 + $this->getTwenty()->getSixty()->getIndex() * 3 - $this->getSixtyCycle()->getIndex());
    }

    /**
     * 太岁方位
     *
     * @return Direction 方位
     */
    function getJupiterDirection(): Direction
    {
        return Direction::fromIndex([0, 7, 7, 2, 3, 3, 8, 1, 1, 6, 0, 0][$this->getSixtyCycle()->getEarthBranch()->getIndex()]);
    }

    /**
     * 首月（五虎遁：甲己之年丙作首，乙庚之岁戊为头，丙辛必定寻庚起，丁壬壬位顺行流，若问戊癸何方发，甲寅之上好追求。）
     *
     * @return SixtyCycleMonth 干支月
     */
    function getFirstMonth(): SixtyCycleMonth
    {
        $h = HeavenStem::fromIndex(($this->getSixtyCycle()->getHeavenStem()->getIndex() + 1) * 2);
        return new SixtyCycleMonth($this, SixtyCycle::fromName(sprintf('%s寅', $h->getName())));
    }

    /**
     * 干支月列表
     *
     * @return SixtyCycleMonth[] 干支月列表
     */
    function getMonths(): array
    {
        $l = array();
        $m = $this->getFirstMonth();
        $l[] = $m;
        for ($i = 1; $i < 12; $i++) {
            $l[] = $m->next($i);
        }
        return $l;
    }
}
