<?php

namespace com\tyme\eightchar\provider\impl;


use com\tyme\eightchar\ChildLimitInfo;
use com\tyme\eightchar\provider\ChildLimitProvider;
use com\tyme\ExtendTrait;
use com\tyme\solar\SolarMonth;
use com\tyme\solar\SolarTime;

/**
 * 童限计算抽象
 * @author 6tail
 * @package com\tyme\eightchar\provider\impl
 */
abstract class AbstractChildLimitProvider implements ChildLimitProvider
{
    use ExtendTrait;

    function next(SolarTime $birthTime, int $addYear, int $addMonth, int $addDay, int $addHour, int $addMinute, int $addSecond): ChildLimitInfo
    {
        $d = $birthTime->getDay() + $addDay;
        $h = $birthTime->getHour() + $addHour;
        $mi = $birthTime->getMinute() + $addMinute;
        $s = $birthTime->getSecond() + $addSecond;
        $mi += intdiv($s, 60);
        $s %= 60;
        $h += intdiv($mi, 60);
        $mi %= 60;
        $d += intdiv($h, 24);
        $h %= 24;

        $sm = SolarMonth::fromYm($birthTime->getYear() + $addYear, $birthTime->getMonth())->next($addMonth);

        $dc = $sm->getDayCount();
        while ($d > $dc) {
            $d -= $dc;
            $sm = $sm->next(1);
            $dc = $sm->getDayCount();
        }

        return new ChildLimitInfo($birthTime, SolarTime::fromYmdHms($sm->getYear(), $sm->getMonth(), $d, $h, $mi, $s), $addYear, $addMonth, $addDay, $addHour, $addMinute);
    }
}
