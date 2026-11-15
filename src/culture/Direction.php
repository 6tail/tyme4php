<?php

namespace com\tyme\culture;


use com\tyme\LoopTyme;
use com\tyme\sixtycycle\SixtyCycle;

/**
 * 方位
 * @author 6tail
 * @package com\tyme\culture
 */
class Direction extends LoopTyme
{
    /**
     * @var string[] 依据后天八卦排序（0坎北, 1坤西南, 2震东, 3巽东南, 4中, 5乾西北, 6兑西, 7艮东北, 8离南）
     */
    static array $NAMES = ['北', '西南', '东', '东南', '中', '西北', '西', '东北', '南'];

    protected function __construct(?int $index = null, ?string $name = null)
    {
        if ($index !== null) {
            parent::__construct(static::$NAMES, $index);
        } else if ($name !== null) {
            parent::__construct(static::$NAMES, null, $name);
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

    static function monthJupiter(SixtyCycle $month): static
    {
        $n = [7, -1, 1, 3][$month->getEarthBranch()->next(-2)->getIndex() % 4];
        return $n == -1 ? $month->getHeavenStem()->getDirection() : static::fromIndex($n);
    }

    static function dayJupiter(Direction $yearJupiter, SixtyCycle $day): static
    {
        $index = $day->getIndex();
        return $index % 12 < 6 ? Element::fromIndex(intdiv($index, 12))->getDirection() : $yearJupiter;
    }

    function next(int $n): static
    {
        return static::fromIndex($this->nextIndex($n));
    }

    /**
     * 九野
     *
     * @return Land 九野
     */
    function getLand(): Land
    {
        return Land::fromIndex($this->index);
    }

    /**
     * 五行
     *
     * @return Element 五行
     */
    function getElement(): Element
    {
        return Element::fromIndex([4, 2, 0, 0, 2, 3, 3, 2, 1][$this->index]);
    }
}
