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
    protected function indexOf(?int $index = null, ?string $name = null, ?int $size = null): int
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

    /**
     * 校验值是否在指定范围内
     *
     * @param int $value 待校验的值
     * @param int $min   最小值（包含）
     * @param int $max   最大值（包含）
     * @param string $field 字段名称，用于异常提示
     */
    protected static function validateRange(int $value, int $min, int $max, string $field): void
    {
        if ($value < $min || $value > $max) {
            throw new InvalidArgumentException('illegal ' . $field . ': ' . $value);
        }
    }
}
