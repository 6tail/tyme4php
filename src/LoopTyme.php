<?php

namespace com\tyme;


use InvalidArgumentException;

/**
 * 可轮回的Tyme
 * @author 6tail
 * @package com\tyme
 */
abstract class LoopTyme extends AbstractTyme
{

    /**
     * @var string[] 名称列表
     */
    protected array $names;

    /**
     * @var int 索引，从0开始
     */
    protected int $index;

    /**
     * 初始化
     *
     * @param string[] $names 名称列表
     * @param int|null $index 索引，支持负数，自动轮转
     * @param string|null $name 名称
     */
    protected function __construct(array $names, int $index = null, string $name = null)
    {
        $this->names = $names;
        if ($index !== null) {
            $this->index = $this->indexOf($index);
        } else if ($name !== null) {
            $this->index = $this->indexOf(null, $name);
        }
    }

    /**
     * 名称
     *
     * @return string 名称
     */
    function getName(): string
    {
        return $this->names[$this->index];
    }

    /**
     * 索引
     *
     * @return int 索引，从0开始
     */
    function getIndex(): int
    {
        return $this->index;
    }

    /**
     * 数量
     *
     * @return int 数量
     */
    function getSize(): int
    {
        return count($this->names);
    }

    protected function indexOf(int $index = null, string $name = null, int $size = null): int
    {
        if ($index !== null) {
            if ($size === null) {
                return parent::indexOf($index, null, $this->getSize());
            } else {
                return parent::indexOf($index, null, $size);
            }
        } else if ($name !== null) {
            // 传了name，则忽略size
            for ($i = 0, $j = $this->getSize(); $i < $j; $i++) {
                if ($this->names[$i] == $name) {
                    return $i;
                }
            }
            throw new InvalidArgumentException(sprintf('illegal name: %d', $name));
        }
        throw new InvalidArgumentException('need index or name');
    }

    /**
     * 推移后的索引
     *
     * @param int $n 推移步数
     * @return int 索引，从0开始
     */
    protected function nextIndex(int $n): int
    {
        return $this->indexOf($this->index + $n);
    }

}
