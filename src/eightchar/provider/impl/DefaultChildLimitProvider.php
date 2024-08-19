<?php

namespace com\tyme\eightchar\provider\impl;


use com\tyme\eightchar\ChildLimitInfo;
use com\tyme\eightchar\provider\ChildLimitProvider;
use com\tyme\solar\SolarMonth;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;

/**
 * 默认的童限计算
 * @author 6tail
 * @package com\tyme\eightchar\provider\impl
 */
class DefaultChildLimitProvider implements ChildLimitProvider
{
    function getInfo(SolarTime $birthTime, SolarTerm $term): ChildLimitInfo
    {
        // 出生时刻和节令时刻相差的秒数
        $seconds = abs($term->getJulianDay()->getSolarTime()->subtract($birthTime));
        // 3天 = 1年，3天=60*60*24*3秒=259200秒 = 1年
        $year = intdiv($seconds, 259200);
        $seconds %= 259200;
        // 1天 = 4月，1天=60*60*24秒=86400秒 = 4月，85400秒/4=21600秒 = 1月
        $month = intdiv($seconds, 21600);
        $seconds %= 21600;
        // 1时 = 5天，1时=60*60秒=3600秒 = 5天，3600秒/5=720秒 = 1天
        $day = intdiv($seconds, 720);
        $seconds %= 720;
        // 1分 = 2时，60秒 = 2时，60秒/2=30秒 = 1时
        $hour = intdiv($seconds, 30);
        $seconds %= 30;
        // 1秒 = 2分，1秒/2=0.5秒 = 1分
        $minute = $seconds * 2;

        $d = $birthTime->getDay() + $day;
        $h = $birthTime->getHour() + $hour;
        $mi = $birthTime->getMinute() + $minute;
        $h += intdiv($mi, 60);
        $mi %= 60;
        $d += intdiv($h, 24);
        $h %= 24;

        $sm = SolarMonth::fromYm($birthTime->getYear() + $year, $birthTime->getMonth())->next($month);

        $dc = $sm->getDayCount();
        while ($d > $dc) {
            $d -= $dc;
            $sm = $sm->next(1);
            $dc = $sm->getDayCount();
        }

        return new ChildLimitInfo($birthTime, SolarTime::fromYmdHms($sm->getYear(), $sm->getMonth(), $d, $h, $mi, $birthTime->getSecond()), $year, $month, $day, $hour, $minute);
    }
}
