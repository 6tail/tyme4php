<?php

namespace com\tyme\culture\dog;


use com\tyme\LoopTyme;

/**
 * 三伏
 * @author 6tail
 * @package com\tyme\culture\dog
 */
class Dog extends LoopTyme
{
    static array $NAMES = ['初伏', '中伏', '末伏'];

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
}
