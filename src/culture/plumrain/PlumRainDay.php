<?php

namespace com\tyme\culture\plumrain;


use com\tyme\AbstractCultureDay;

/**
 * 梅雨天
 * @author 6tail
 * @package com\tyme\culture\plumrain
 */
class PlumRainDay extends AbstractCultureDay
{
    function __construct(PlumRain $nine, int $dayIndex)
    {
        parent::__construct($nine, $dayIndex);
    }

    /**
     * 梅雨
     *
     * @return PlumRain 梅雨
     */
    function getPlumRain(): PlumRain
    {
        return $this->culture;
    }

    function __toString(): string
    {
        return $this->getPlumRain()->getIndex() == 0 ? parent::__toString() : $this->culture->getName();
    }
}
