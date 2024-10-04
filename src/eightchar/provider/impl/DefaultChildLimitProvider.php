<?php

namespace com\tyme\eightchar\provider\impl;


use com\tyme\eightchar\ChildLimitInfo;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;

/**
 * 默认的童限计算
 * @author 6tail
 * @package com\tyme\eightchar\provider\impl
 */
class DefaultChildLimitProvider extends AbstractChildLimitProvider
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
        return $this->next($birthTime, $year, $month, $day, $hour, $minute, 0);
    }
}
