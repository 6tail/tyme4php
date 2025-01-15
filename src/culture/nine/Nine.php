<?php

namespace com\tyme\culture\nine;


use com\tyme\LoopTyme;

/**
 * 数九
 * @author 6tail
 * @package com\tyme\culture\nine
 */
class Nine extends LoopTyme
{
    static array $NAMES = ['一九', '二九', '三九', '四九', '五九', '六九', '七九', '八九', '九九'];

    protected function __construct(?int $index = null, ?string $name = null)
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
