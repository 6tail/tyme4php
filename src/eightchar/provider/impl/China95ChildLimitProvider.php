<?php

namespace com\tyme\eightchar\provider\impl;


use com\tyme\eightchar\ChildLimitInfo;
use com\tyme\eightchar\provider\ChildLimitProvider;
use com\tyme\solar\SolarMonth;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;

/**
 * 元亨利贞的童限计算
 * @author 6tail
 * @package com\tyme\eightchar\provider\impl
 */
class China95ChildLimitProvider implements ChildLimitProvider
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

        $sm = SolarMonth::fromYm($birthTime->getYear() + $year, $birthTime->getMonth())->next($month);

        $d = $birthTime->getDay() + $day;
        $dc = $sm->getDayCount();
        if ($d > $dc) {
            $d -= $dc;
            $sm = $sm->next(1);
        }

        return new ChildLimitInfo($birthTime, SolarTime::fromYmdHms($sm->getYear(), $sm->getMonth(), $d, $birthTime->getHour(), $birthTime->getMinute(), $birthTime->getSecond()), $year, $month, $day, 0, 0);
    }
}
