<?php

namespace com\tyme\culture\ren;


use com\tyme\culture\Element;
use com\tyme\culture\Luck;
use com\tyme\LoopTyme;

/**
 * 小六壬
 * @author 6tail
 * @package com\tyme\culture\ren
 */
class MinorRen extends LoopTyme
{
    static array $NAMES = ['大安', '留连', '速喜', '赤口', '小吉', '空亡'];

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
     * 吉凶
     * @return Luck 吉凶
     */
    function getLuck(): Luck
    {
        return Luck::fromIndex($this->index % 2);
    }

    /**
     * 五行
     * @return Element 五行
     */
    function getElement(): Element
    {
        return Element::fromIndex([0, 4, 1, 3, 0, 2][$this->index]);
    }
}
