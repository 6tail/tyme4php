<?php

namespace com\tyme\sixtycycle;


use com\tyme\AbstractCulture;
use com\tyme\enums\HideHeavenStemType;

/**
 * 藏干（即人元，司令取天干，分野取天干的五行）
 * @author 6tail
 * @package com\tyme\sixtycycle
 */
class HideHeavenStem extends AbstractCulture
{

    /**
     * @var HeavenStem 天干
     */
    protected HeavenStem $heavenStem;

    /**
     * @var HideHeavenStemType 藏干类型
     */
    protected HideHeavenStemType $type;

    function __construct(HeavenStem|string|int $heavenStem, HideHeavenStemType $type)
    {
        if (is_string($heavenStem)) {
            $this->heavenStem = HeavenStem::fromName($heavenStem);
        } elseif (is_int($heavenStem)) {
            $this->heavenStem = HeavenStem::fromIndex($heavenStem);
        } else {
            $this->heavenStem = $heavenStem;
        }
        $this->type = $type;
    }

    /**
     * 天干
     *
     * @return HeavenStem 天干
     */
    function getHeavenStem(): HeavenStem
    {
        return $this->heavenStem;
    }

    /**
     * 藏干类型
     *
     * @return HideHeavenStemType 藏干类型
     */
    function getType(): HideHeavenStemType
    {
        return $this->type;
    }

    function getName(): string
    {
        return $this->heavenStem->getName();
    }
}
