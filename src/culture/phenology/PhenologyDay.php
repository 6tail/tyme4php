<?php

namespace com\tyme\culture\phenology;


use com\tyme\AbstractCultureDay;

/**
 * 七十二候
 * @author 6tail
 * @package com\tyme\culture\dog
 */
class PhenologyDay extends AbstractCultureDay
{
    function __construct(Phenology $phenology, int $dayIndex)
    {
        parent::__construct($phenology, $dayIndex);
    }

    /**
     * 候
     *
     * @return Phenology 候
     */
    function getPhenology(): Phenology
    {
        return $this->culture;
    }
}
