<?php

namespace com\tyme\culture\star\twelve;


use com\tyme\LoopTyme;

/**
 * 黄道黑道十二神
 * @author 6tail
 * @package com\tyme\culture\star\twelve
 */
class TwelveStar extends LoopTyme
{
    static array $NAMES = ['青龙', '明堂', '天刑', '朱雀', '金匮', '天德', '白虎', '玉堂', '天牢', '玄武', '司命', '勾陈'];

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
     * 黄道黑道
     *
     * @return Ecliptic 黄道黑道
     */
    function getEcliptic(): Ecliptic
    {
        return Ecliptic::fromIndex([0, 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 1][$this->index]);
    }

}
