<?php

namespace com\tyme\culture\nine;


use com\tyme\AbstractCultureDay;

/**
 * 数九天
 * @author 6tail
 * @package com\tyme\culture\nine
 */
class NineDay extends AbstractCultureDay
{
    function __construct(Nine $nine, int $dayIndex)
    {
        parent::__construct($nine, $dayIndex);
    }

    /**
     * 数九
     *
     * @return Nine 数九
     */
    function getNine(): Nine
    {
        return $this->culture;
    }
}
