<?php

namespace com\tyme\culture\star\twelve;


use com\tyme\culture\Luck;
use com\tyme\LoopTyme;

/**
 * 黄道黑道
 * @author 6tail
 * @package com\tyme\culture\star\twelve
 */
class Ecliptic extends LoopTyme
{
    static array $NAMES = ['黄道', '黑道'];

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
     * 吉凶
     *
     * @return Luck 吉凶
     */
    function getLuck(): Luck
    {
        return Luck::fromIndex($this->index);
    }
}
