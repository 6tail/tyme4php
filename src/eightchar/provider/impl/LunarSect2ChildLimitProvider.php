<?php

namespace com\tyme\eightchar\provider\impl;


use com\tyme\eightchar\ChildLimitInfo;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;

/**
 * Lunar的流派2童限计算（按分钟数计算）
 * @author 6tail
 * @package com\tyme\eightchar\provider\impl
 */
class LunarSect2ChildLimitProvider extends AbstractChildLimitProvider
{
    function getInfo(SolarTime $birthTime, SolarTerm $term): ChildLimitInfo
    {
        // 出生时刻和节令时刻相差的分钟数
        $minutes = intdiv(abs($term->getJulianDay()->getSolarTime()->subtract($birthTime)), 60);
        $year = intdiv($minutes, 4320);
        $minutes %= 4320;
        $month = intdiv($minutes, 360);
        $minutes %= 360;
        $day = intdiv($minutes, 12);
        $minutes %= 12;
        $hour = $minutes * 2;
        return $this->next($birthTime, $year, $month, $day, $hour, 0, 0);
    }
}
