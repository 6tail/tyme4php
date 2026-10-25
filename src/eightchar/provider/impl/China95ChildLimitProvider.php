<?php

namespace com\tyme\eightchar\provider\impl;


use com\tyme\eightchar\ChildLimitInfo;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;

/**
 * 元亨利贞的童限计算
 *
 * @author 6tail
 * @package com\tyme\eightchar\provider\impl
 */
class China95ChildLimitProvider extends LunarSect2ChildLimitProvider
{
    function getInfo(SolarTime $birthTime, SolarTerm $term): ChildLimitInfo
    {
        $t = $this->compute($birthTime, $term);
        return $this->next($birthTime, $t->getYear(), $t->getMonth(), $t->getDay(), 0, 0, 0);
    }
}
