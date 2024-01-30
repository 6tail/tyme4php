<?php

namespace com\tyme\lunar;


use com\tyme\LoopTyme;

/**
 * 农历季节
 * @author 6tail
 * @package com\tyme\lunar
 */
class LunarSeason extends LoopTyme
{
    static array $NAMES = ['孟春', '仲春', '季春', '孟夏', '仲夏', '季夏', '孟秋', '仲秋', '季秋', '孟冬', '仲冬', '季冬'];

    protected function __construct(int $index = null, string $name = null)
    {
        if ($index != null) {
            parent::__construct(self::$NAMES, $index);
        } else if ($name != null) {
            parent::__construct(self::$NAMES, $name);
        }
    }

    static function fromIndex(int $index): static
    {
        return new static($index);
    }

    static function fromName(string $name): static
    {
        return new static($name);
    }

    function next(int $n): static
    {
        return self::fromIndex($this->nextIndex($n));
    }
}
