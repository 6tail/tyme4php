<?php

namespace com\tyme\sixtycycle;


use com\tyme\culture\Direction;
use com\tyme\culture\Element;
use com\tyme\culture\pengzu\PengZuEarthBranch;
use com\tyme\culture\Zodiac;
use com\tyme\enums\YinYang;
use com\tyme\LoopTyme;

/**
 * 地支
 * @author 6tail
 * @package com\tyme\sixtycycle
 */
class EarthBranch extends LoopTyme
{
    static array $NAMES = ['子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥'];

    protected function __construct(int $index = null, string $name = null)
    {
        if ($index !== null) {
            parent::__construct(self::$NAMES, $index);
        } else if ($name !== null) {
            parent::__construct(self::$NAMES, null, $name);
        }
    }

    static function fromIndex(int $index): static
    {
        return new static($index);
    }

    static function fromName(string $name): static
    {
        return new static(null, $name);
    }

    function next(int $n): static
    {
        return self::fromIndex($this->nextIndex($n));
    }

    /**
     * 五行
     *
     * @return Element 五行
     */
    function getElement(): Element
    {
        return Element::fromIndex([4, 2, 0, 0, 2, 1, 1, 2, 3, 3, 2, 4][$this->index]);
    }

    /**
     * 阴阳
     *
     * @return YinYang 阴阳
     */
    function getYinYang(): YinYang
    {
        return $this->index % 2 == 0 ? YinYang::YANG : YinYang::YIN;
    }

    /**
     * 藏干之本气
     *
     * @return HeavenStem 天干
     */
    function getHideHeavenStemMain(): HeavenStem
    {
        return HeavenStem::fromIndex([9, 5, 0, 1, 4, 2, 3, 5, 6, 7, 4, 8][$this->index]);
    }

    /**
     * 藏干之中气，无中气返回null
     *
     * @return ?HeavenStem 天干
     */
    function getHideHeavenStemMiddle(): ?HeavenStem
    {
        $n = [-1, 9, 2, -1, 1, 6, 5, 3, 8, -1, 7, 0][$this->index];
        return $n == -1 ? null : HeavenStem::fromIndex($n);
    }

    /**
     * 藏干之余气，无余气返回null
     *
     * @return ?HeavenStem 天干
     */
    function getHideHeavenStemResidual(): ?HeavenStem
    {
        $n = [-1, 7, 4, -1, 9, 4, -1, 1, 4, -1, 3, -1][$this->index];
        return $n == -1 ? null : HeavenStem::fromIndex($n);
    }

    /**
     * 生肖
     *
     * @return Zodiac 生肖
     */
    function getZodiac(): Zodiac
    {
        return Zodiac::fromIndex($this->index);
    }

    /**
     * 方位
     *
     * @return Direction 方位
     */
    function getDirection(): Direction
    {
        return Direction::fromIndex([0, 4, 2, 2, 4, 8, 8, 4, 6, 6, 4, 0][$this->index]);
    }

    /**
     * 相冲的地支（子午冲，丑未冲，寅申冲，辰戌冲，卯酉冲，巳亥冲）
     *
     * @return EarthBranch 地支
     */
    function getOpposite(): static
    {
        return $this->next(6);
    }

    /**
     * 煞（逢巳日、酉日、丑日必煞东；亥日、卯日、未日必煞西；申日、子日、辰日必煞南；寅日、午日、戌日必煞北。）
     *
     * @return Direction 方位
     */
    function getOminous(): Direction
    {
        return Direction::fromIndex([8, 2, 0, 6][$this->index % 4]);
    }

    /**
     * 地支彭祖百忌
     *
     * @return PengZuEarthBranch 地支彭祖百忌
     */
    function getPengZuEarthBranch(): PengZuEarthBranch
    {
        return PengZuEarthBranch::fromIndex($this->index);
    }
}
