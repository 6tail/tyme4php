<?php

namespace com\tyme\eightchar;


use com\tyme\AbstractTyme;
use com\tyme\lunar\LunarYear;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\sixtycycle\SixtyCycleYear;

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
        return $this->childLimit->getEndSixtyCycleYear()->getYear() - $this->childLimit->getStartSixtyCycleYear()->getYear() + 1 + $this->index * 10;
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
     * @deprecated
     * @see getStartSixtyCycleYear()
     */
    function getStartLunarYear(): LunarYear
    {
        return $this->childLimit->getEndLunarYear()->next($this->index * 10);
    }

    /**
     * 开始干支年
     *
     * @return SixtyCycleYear 干支年
     */
    function getStartSixtyCycleYear(): SixtyCycleYear
    {
        return $this->childLimit->getEndSixtyCycleYear()->next($this->index * 10);
    }

    /**
     * 结束农历年
     *
     * @return LunarYear 农历年
     * @deprecated
     * @see getEndSixtyCycleYear()
     */
    function getEndLunarYear(): LunarYear
    {
        return $this->getStartLunarYear()->next(9);
    }

    /**
     * 结束干支年
     *
     * @return SixtyCycleYear 干支年
     */
    function getEndSixtyCycleYear(): SixtyCycleYear
    {
        return $this->getStartSixtyCycleYear()->next(9);
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        return $this->childLimit->getEightChar()->getMonth()->next($this->childLimit->isForward() ? $this->index + 1 : -$this->index - 1);
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
