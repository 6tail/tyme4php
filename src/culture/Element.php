<?php

namespace com\tyme\culture;


use com\tyme\LoopTyme;

/**
 * 五行
 * @author 6tail
 * @package com\tyme\culture
 */
class Element extends LoopTyme
{
    static array $NAMES = ['木', '火', '土', '金', '水'];

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
     * 我生者
     *
     * @return Element 五行
     */
    function getReinforce(): static
    {
        return $this->next(1);
    }

    /**
     * 我克者
     *
     * @return Element 五行
     */
    function getRestrain(): static
    {
        return $this->next(2);
    }

    /**
     * 生我者
     *
     * @return Element 五行
     */
    function getReinforced(): static
    {
        return $this->next(-1);
    }

    /**
     * 克我者
     *
     * @return Element 五行
     */
    function getRestrained(): static
    {
        return $this->next(-2);
    }

    /**
     * 方位
     * @return Direction 方位
     */
    function getDirection(): Direction
    {
        return Direction::fromIndex([2, 8, 4, 6, 0][$this->index]);
    }
}
