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
    protected function __construct(?int $index = null, ?string $name = null)
    {
        if ($index !== null) {
            parent::__construct($index);
        } else if ($name !== null) {
            parent::__construct(null, str_replace('铁', '金', $name));
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

    function getName(): string
    {
        return str_replace('金', '铁', parent::getName());
    }
}
