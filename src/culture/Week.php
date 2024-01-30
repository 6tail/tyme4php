<?php

namespace com\tyme\culture;


use com\tyme\culture\star\seven\SevenStar;
use com\tyme\LoopTyme;

/**
 * 星期
 * @author 6tail
 * @package com\tyme\culture
 */
class Week extends LoopTyme
{
    static array $NAMES = ['日', '一', '二', '三', '四', '五', '六'];

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


    /**
     * 七曜
     *
     * @return SevenStar 七曜
     */
    function getSevenStar(): SevenStar
    {
        return SevenStar::fromIndex($this->index);
    }
}
