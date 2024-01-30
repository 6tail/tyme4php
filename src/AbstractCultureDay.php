<?php

namespace com\tyme;


/**
 * 带天索引的传统文化抽象
 * @author 6tail
 * @package com\tyme
 */
abstract class AbstractCultureDay extends AbstractCulture
{
    /**
     * @var AbstractCulture 传统文化
     */
    protected AbstractCulture $culture;

    /**
     * @var int 天索引
     */
    protected int $dayIndex;

    protected function __construct(AbstractCulture $culture, int $dayIndex)
    {
        $this->culture = $culture;
        $this->dayIndex = $dayIndex;
    }

    /**
     * 天索引
     *
     * @return int 索引
     */
    function getDayIndex(): int
    {
        return $this->dayIndex;
    }

    protected function getCulture(): Culture
    {
        return $this->culture;
    }

    function __toString(): string
    {
        return sprintf('%s第%d天', $this->culture, $this->dayIndex + 1);
    }

    function getName(): string
    {
        return $this->culture->getName();
    }
}
