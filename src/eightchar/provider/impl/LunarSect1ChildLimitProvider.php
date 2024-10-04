<?php

namespace com\tyme\eightchar\provider\impl;


use com\tyme\eightchar\ChildLimitInfo;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;

/**
 * Lunar的流派1童限计算（按天数和时辰数计算，3天1年，1天4个月，1时辰10天）
 * @author 6tail
 * @package com\tyme\eightchar\provider\impl
 */
class LunarSect1ChildLimitProvider extends AbstractChildLimitProvider
{
    function getInfo(SolarTime $birthTime, SolarTerm $term): ChildLimitInfo
    {
        $termTime = $term->getJulianDay()->getSolarTime();
        $end = $termTime;
        $start = $birthTime;
        if ($birthTime->isAfter($termTime)) {
            $end = $birthTime;
            $start = $termTime;
        }
        $endTimeZhiIndex = ($end->getHour() == 23) ? 11 : $end->getLunarHour()->getIndexInDay();
        $startTimeZhiIndex = ($start->getHour() == 23) ? 11 : $start->getLunarHour()->getIndexInDay();
        // 时辰差
        $hourDiff = $endTimeZhiIndex - $startTimeZhiIndex;
        // 天数差
        $dayDiff = $end->getSolarDay()->subtract($start->getSolarDay());
        if ($hourDiff < 0) {
            $hourDiff += 12;
            $dayDiff--;
        }
        $monthDiff = intdiv($hourDiff * 10, 30);
        $month = $dayDiff * 4 + $monthDiff;
        $day = $hourDiff * 10 - $monthDiff * 30;
        $year = intdiv($month, 12);
        $month = $month - $year * 12;
        return $this->next($birthTime, $year, $month, $day, 0, 0, 0);
    }
}
