<?php

namespace com\tyme\culture;


use com\tyme\LoopTyme;
use com\tyme\sixtycycle\EarthBranch;

/**
 * 生肖
 * @author 6tail
 * @package com\tyme\culture
 */
class Zodiac extends LoopTyme
{
    static array $NAMES = ['鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪'];

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

    function next(int $n): static
    {
        return static::fromIndex($this->nextIndex($n));
    }

    /**
     * 地支
     *
     * @return EarthBranch 地支
     */
    function getEarthBranch(): EarthBranch
    {
        return EarthBranch::fromIndex($this->index);
    }
}
