<?php

namespace com\tyme\sixtycycle;


use com\tyme\AbstractCultureDay;

/**
 * 人元司令分野（地支藏干+天索引）
 * @author 6tail
 * @package com\tyme\sixycycle
 */
class HideHeavenStemDay extends AbstractCultureDay
{
    function __construct(HideHeavenStem $hideHeavenStem, int $dayIndex)
    {
        parent::__construct($hideHeavenStem, $dayIndex);
    }

    /**
     * 藏干
     *
     * @return HideHeavenStem 藏干
     */
    function getHideHeavenStem(): HideHeavenStem
    {
        return $this->culture;
    }

    function getName(): string
    {
        $heavenStem = $this->getHideHeavenStem()->getHeavenStem();
        return $heavenStem->getName() . $heavenStem->getElement()->getName();
    }

    function __toString(): string
    {
        return sprintf('%s第%d天', $this->getName(), $this->getDayIndex() + 1);
    }
}
