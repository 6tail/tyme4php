<?php

namespace com\tyme\unit;


use com\tyme\culture\Direction;
use com\tyme\culture\star\nine\NineStar;
use com\tyme\culture\Twenty;
use com\tyme\sixtycycle\SixtyCycle;

/**
 * 传统年抽象
 *
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class AbstractTraditionalYear extends AbstractYear
{
    function __construct(int $year)
    {
        parent::__construct($year);
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
}
