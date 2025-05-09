<?php

namespace com\tyme\culture\star\nine;


use com\tyme\LoopTyme;

/**
 * 北斗九星
 * @author 6tail
 * @package com\tyme\culture\star\nine
 */
class Dipper extends LoopTyme
{
    static array $NAMES = ['天枢', '天璇', '天玑', '天权', '玉衡', '开阳', '摇光', '洞明', '隐元'];

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
}
