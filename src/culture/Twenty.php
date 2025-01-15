<?php

namespace com\tyme\culture;


use com\tyme\LoopTyme;

/**
 * 运（20年=1运，3运=1元）
 * @author 6tail
 * @package com\tyme\culture
 */
class Twenty extends LoopTyme
{
    static array $NAMES = ['一运', '二运', '三运', '四运', '五运', '六运', '七运', '八运', '九运'];

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

    /**
     * 元
     * @return Sixty 元
     */
    function getSixty(): Sixty
    {
        return Sixty::fromIndex(intdiv($this->index, 3));
    }
}
