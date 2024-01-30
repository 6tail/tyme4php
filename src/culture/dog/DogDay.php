<?php

namespace com\tyme\culture\dog;


use com\tyme\AbstractCultureDay;

/**
 * 三伏天
 * @author 6tail
 * @package com\tyme\culture\dog
 */
class DogDay extends AbstractCultureDay
{
    function __construct(Dog $dog, int $dayIndex)
    {
        parent::__construct($dog, $dayIndex);
    }

    /**
     * 三伏
     *
     * @return Dog 三伏
     */
    function getDog(): Dog
    {
        return $this->culture;
    }
}
