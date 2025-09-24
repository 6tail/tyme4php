<?php

namespace com\tyme\culture;


use com\tyme\AbstractCultureDay;

/**
 * 月相第几天
 * @author 6tail
 * @package com\tyme\culture
 */
class PhaseDay extends AbstractCultureDay
{
    function __construct(Phase $phase, int $dayIndex)
    {
        parent::__construct($phase, $dayIndex);
    }

    /**
     * 月相
     *
     * @return Phase 月相
     */
    function getPhase(): Phase
    {
        return $this->culture;
    }
}
