<?php

namespace com\tyme\eightchar\provider\impl;


use com\tyme\eightchar\ChildLimitInfo;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;
use com\tyme\unit\SecondUnit;

/**
 * Lunar的流派2童限计算（按分钟数计算）
 *
 * @author 6tail
 * @package com\tyme\eightchar\provider\impl
 */
class LunarSect2ChildLimitProvider extends AbstractChildLimitProvider
{
    protected function compute(SolarTime $birthTime, SolarTerm $term): SecondUnit
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
        return new class($year, $month, $day, $hour, 0, 0) extends SecondUnit {};
    }

    function getInfo(SolarTime $birthTime, SolarTerm $term): ChildLimitInfo
    {
        $t = $this->compute($birthTime, $term);
        return $this->next($birthTime, $t->getYear(), $t->getMonth(), $t->getDay(), $t->getHour(), 0, 0);
    }
}
