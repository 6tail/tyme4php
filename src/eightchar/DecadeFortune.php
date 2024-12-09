<?php

namespace com\tyme\eightchar;


use com\tyme\AbstractTyme;
use com\tyme\lunar\LunarYear;
use com\tyme\sixtycycle\SixtyCycle;

/**
 * 大运（10年1大运）
 * @author 6tail
 * @package com\tyme\eightchar
 */
class DecadeFortune extends AbstractTyme
{
    /**
     * @var ChildLimit 童限
     */
    protected ChildLimit $childLimit;

    /**
     * @var int 序号
     */
    protected int $index;

    protected function __construct(ChildLimit $childLimit, int $index)
    {
        $this->childLimit = $childLimit;
        $this->index = $index;
    }

    static function fromChildLimit(ChildLimit $childLimit, int $index): static
    {
        return new static($childLimit, $index);
    }

    /**
     * 开始年龄
     *
     * @return int 开始年龄
     */
    function getStartAge(): int
    {
        return $this->childLimit->getEndTime()->getYear() - $this->childLimit->getStartTime()->getYear() + 1 + $this->index * 10;
    }

    /**
     * 结束年龄
     *
     * @return int 结束年龄
     */
    function getEndAge(): int
    {
        return $this->getStartAge() + 9;
    }

    /**
     * 开始农历年
     *
     * @return LunarYear 农历年
     */
    function getStartLunarYear(): LunarYear
    {
        return $this->childLimit->getEndLunarYear()->next($this->index * 10);
    }

    /**
     * 结束农历年
     *
     * @return LunarYear 农历年
     */
    function getEndLunarYear(): LunarYear
    {
        return $this->getStartLunarYear()->next(9);
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        $n = $this->index + 1;
        return $this->childLimit->getEightChar()->getMonth()->next($this->childLimit->isForward() ? $n : -$n);
    }

    function getName(): string
    {
        return $this->getSixtyCycle()->getName();
    }

    function next(int $n): static
    {
        return self::fromChildLimit($this->childLimit, $this->index + $n);
    }

    /**
     * 开始小运
     *
     * @return Fortune 小运
     */
    function getStartFortune(): Fortune
    {
        return Fortune::fromChildLimit($this->childLimit, $this->index * 10);
    }

}
