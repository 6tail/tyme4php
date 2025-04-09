<?php

namespace com\tyme\eightchar\provider\impl;


use com\tyme\eightchar\EightChar;
use com\tyme\eightchar\provider\EightCharProvider;
use com\tyme\ExtendTrait;
use com\tyme\lunar\LunarHour;

/**
 * 默认的八字计算（晚子时算第二天）
 * @author 6tail
 * @package com\tyme\eightchar\provider\impl
 */
class DefaultEightCharProvider implements EightCharProvider
{
    use ExtendTrait;

    function getEightChar(LunarHour $hour): EightChar
    {
        return $hour->getSixtyCycleHour()->getEightChar();
    }
}
