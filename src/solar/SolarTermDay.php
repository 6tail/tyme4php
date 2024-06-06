<?php

namespace com\tyme\solar;


use com\tyme\AbstractCultureDay;

/**
 * 节气第几天
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarTermDay extends AbstractCultureDay
{
    function __construct(SolarTerm $solarTerm, int $dayIndex)
    {
        parent::__construct($solarTerm, $dayIndex);
    }

    /**
     * 节气
     *
     * @return SolarTerm 节气
     */
    function getSolarTerm(): SolarTerm
    {
        return $this->culture;
    }
}
