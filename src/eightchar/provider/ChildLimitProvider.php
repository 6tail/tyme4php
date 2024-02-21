<?php

namespace com\tyme\eightchar\provider;


use com\tyme\eightchar\ChildLimitInfo;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;

/**
 * 童限计算接口
 * @author 6tail
 * @package com\tyme\eightchar\provider
 */
interface ChildLimitProvider
{
    /**
     * 童限信息
     * @param SolarTime $birthTime 出生公历时刻
     * @param SolarTerm $term 节令
     * @return ChildLimitInfo 童限信息
     */
    function getInfo(SolarTime $birthTime, SolarTerm $term): ChildLimitInfo;
}
