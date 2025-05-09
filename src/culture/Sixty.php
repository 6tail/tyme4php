<?php

namespace com\tyme\culture;


use com\tyme\LoopTyme;

/**
 * 元（60年=1元）
 * @author 6tail
 * @package com\tyme\culture
 */
class Sixty extends LoopTyme
{
    static array $NAMES = ['上元', '中元', '下元'];

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
