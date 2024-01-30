<?php

namespace com\tyme\culture;


use com\tyme\LoopTyme;

/**
 * 旬
 * @author 6tail
 * @package com\tyme\culture
 */
class Ten extends LoopTyme
{
    static array $NAMES = ['甲子', '甲戌', '甲申', '甲午', '甲辰', '甲寅'];

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
