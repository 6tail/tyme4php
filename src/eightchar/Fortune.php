<?php

namespace com\tyme\eightchar;


use com\tyme\AbstractTyme;
use com\tyme\lunar\LunarYear;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\sixtycycle\SixtyCycleYear;

/**
 * 小运
 * @author 6tail
 * @package com\tyme\eightchar
 */
class Fortune extends AbstractTyme
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
     * 年龄
     *
     * @return int 年龄
     */
    function getAge(): int
    {
        return $this->childLimit->getEndSixtyCycleYear()->getYear() - $this->childLimit->getStartSixtyCycleYear()->getYear() + 1 + $this->index;
    }

    /**
     * 农历年
     *
     * @return LunarYear 农历年
     * @deprecated
     * @see getSixtyCycleYear()
     */
    function getLunarYear(): LunarYear
    {
        return $this->childLimit->getEndLunarYear()->next($this->index);
    }

    /**
     * 干支年
     *
     * @return SixtyCycleYear 干支年
     */
    function getSixtyCycleYear(): SixtyCycleYear
    {
        return $this->childLimit->getEndSixtyCycleYear()->next($this->index);
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        $n = $this->getAge();
        return $this->childLimit->getEightChar()->getHour()->next($this->childLimit->isForward() ? $n : -$n);
    }

    function getName(): string
    {
        return $this->getSixtyCycle()->getName();
    }

    function next(int $n): static
    {
        return self::fromChildLimit($this->childLimit, $this->index + $n);
    }

}
