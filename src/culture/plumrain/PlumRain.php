<?php

namespace com\tyme\culture\plumrain;


use com\tyme\LoopTyme;

/**
 * 梅雨
 * @author 6tail
 * @package com\tyme\culture\plumrain
 */
class PlumRain extends LoopTyme
{
    static array $NAMES = ['入梅', '出梅'];

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
