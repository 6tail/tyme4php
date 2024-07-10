<?php

namespace com\tyme;


use InvalidArgumentException;

/**
 * 传统文化抽象
 * @author 6tail
 * @package com\tyme
 */
abstract class AbstractCulture implements Culture
{
    use ExtendTrait;

    function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @param mixed $o 对象
     * @return bool true/false
     */
    function equals(mixed $o): bool
    {
        return $o instanceof Culture && $this->__toString() == $o->__toString();
    }

    /**
     * 转换为不超范围的索引
     *
     * @param int|null $index 索引
     * @param string|null $name 名称
     * @param int|null $size 数量
     * @return int 索引，从0开始
     */
    protected function indexOf(int $index = null, string $name = null, int $size = null): int
    {
        if ($index !== null && $size !== null) {
            $i = $index % $size;
            if ($i < 0) {
                $i += $size;
            }
            return $i;
        }
        throw new InvalidArgumentException(sprintf('invalid name: %s, size: %d', $name, $size));
    }
}
