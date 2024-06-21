<?php

namespace com\tyme\culture\star\six;


use com\tyme\LoopTyme;

/**
 * 六曜（孔明六曜星、小六壬）
 * @author 6tail
 * @package com\tyme\culture\star\six
 */
class SixStar extends LoopTyme
{
    static array $NAMES = ['先胜', '友引', '先负', '佛灭', '大安', '赤口'];

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
