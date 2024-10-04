<?php

namespace com\tyme\eightchar\provider;


use com\tyme\eightchar\EightChar;
use com\tyme\lunar\LunarHour;

/**
 * 八字计算接口
 * @author 6tail
 * @package com\tyme\eightchar\provider
 */
interface EightCharProvider
{
    /**
     * 八字
     * @param LunarHour $hour 农历时辰
     * @return EightChar 八字
     */
    function getEightChar(LunarHour $hour): EightChar;
}
