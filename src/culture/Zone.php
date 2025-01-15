<?php

namespace com\tyme\culture;


use com\tyme\LoopTyme;

/**
 * 宫
 * @author 6tail
 * @package com\tyme\culture
 */
class Zone extends LoopTyme
{
    static array $NAMES = ['东', '北', '西', '南'];

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
     * 方位
     *
     * @return Direction 方位
     */
    function getDirection(): Direction
    {
        return Direction::fromName($this->getName());
    }

    /**
     * 神兽
     *
     * @return Beast 神兽
     */
    function getBeast(): Beast
    {
        return Beast::fromIndex($this->index);
    }
}
