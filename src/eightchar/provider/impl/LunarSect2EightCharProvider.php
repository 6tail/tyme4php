<?php

namespace com\tyme\eightchar\provider\impl;


use com\tyme\eightchar\EightChar;
use com\tyme\eightchar\provider\EightCharProvider;
use com\tyme\ExtendTrait;
use com\tyme\lunar\LunarHour;

/**
 * Lunar流派2的八字计算（晚子时日柱算当天）
 * @author 6tail
 * @package com\tyme\eightchar\provider\impl
 */
class LunarSect2EightCharProvider implements EightCharProvider
{
    use ExtendTrait;

    function getEightChar(LunarHour $hour): EightChar
    {
        $h = $hour->getSixtyCycleHour();
        return new EightChar($h->getYear(), $h->getMonth(), $hour->getLunarDay()->getSixtyCycle(), $h->getSixtyCycle());
    }
}
