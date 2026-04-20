<?php

namespace com\tyme\rabbyung;


use com\tyme\culture\Element;

/**
 * 藏历五行
 * @author 6tail
 * @package com\tyme\rabbyung
 */
class RabByungElement extends Element
{
    static array $NAMES = ['木', '火', '土', '铁', '水'];

    protected function __construct(?int $index = null, ?string $name = null)
    {
        parent::__construct(static::$NAMES, $index, $name);
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

    /**
     * 我生者
     *
     * @return RabByungElement 藏历五行
     */
    function getReinforce(): static
    {
        return $this->next(1);
    }

    /**
     * 我克者
     *
     * @return RabByungElement 藏历五行
     */
    function getRestrain(): static
    {
        return $this->next(2);
    }

    /**
     * 生我者
     *
     * @return RabByungElement 藏历五行
     */
    function getReinforced(): static
    {
        return $this->next(-1);
    }

    /**
     * 克我者
     *
     * @return RabByungElement 藏历五行
     */
    function getRestrained(): static
    {
        return $this->next(-2);
    }
}
