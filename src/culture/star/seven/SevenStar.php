<?php

namespace com\tyme\culture\star\seven;


use com\tyme\culture\Week;
use com\tyme\LoopTyme;

/**
 * 七曜（七政、七纬、七耀）
 * @author 6tail
 * @package com\tyme\culture\star\seven
 */
class SevenStar extends LoopTyme
{
    static array $NAMES = ['日', '月', '火', '水', '木', '金', '土'];

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
     * 星期
     *
     * @return Week 星期
     */
    function getWeek(): Week
    {
        return Week::fromIndex($this->index);
    }
}
