<?php

namespace com\tyme\culture\star\nine;


use com\tyme\culture\Direction;
use com\tyme\culture\Element;
use com\tyme\LoopTyme;

/**
 * 九星
 * @author 6tail
 * @package com\tyme\culture\star\nine
 */
class NineStar extends LoopTyme
{
    static array $NAMES = ['一', '二', '三', '四', '五', '六', '七', '八', '九'];

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

    /**
     * 颜色
     *
     * @return string 颜色
     */
    function getColor(): string
    {
        return ['白', '黑', '碧', '绿', '黄', '白', '赤', '白', '紫'][$this->index];
    }

    /**
     * 五行
     *
     * @return Element 五行
     */
    function getElement(): Element
    {
        return Element::fromIndex([4, 2, 0, 0, 2, 3, 3, 2, 1][$this->index]);
    }

    /**
     * 北斗九星
     *
     * @return Dipper 北斗九星
     */
    function getDipper(): Dipper
    {
        return Dipper::fromIndex($this->index);
    }

    /**
     * 方位
     *
     * @return Direction 方位
     */
    function getDirection(): Direction
    {
        return Direction::fromIndex($this->index);
    }

    function __toString(): string
    {
        return sprintf('%s%s%s', $this->getName(), $this->getColor(), $this->getElement());
    }
}
