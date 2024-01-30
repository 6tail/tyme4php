<?php

namespace com\tyme\culture\fetus;


use com\tyme\LoopTyme;
use com\tyme\lunar\LunarMonth;

/**
 * 逐月胎神（正十二月在床房，二三九十门户中，四六十一灶勿犯，五甲七子八厕凶。）
 * @author 6tail
 * @package com\tyme\culture\fetus
 */
class FetusMonth extends LoopTyme
{
    static array $NAMES = ['占房床', '占户窗', '占门堂', '占厨灶', '占房床', '占床仓', '占碓磨', '占厕户', '占门房', '占房床', '占灶炉', '占房床'];

    protected function __construct(int $index)
    {
        parent::__construct(self::$NAMES, $index);
    }

    static function fromIndex(int $index): static
    {
        return new static($index);
    }

    /**
     * 从农历月初始化
     *
     * @param LunarMonth $lunarMonth 农历月
     * @return FetusMonth|null 逐月胎神
     */
    static function fromLunarMonth(LunarMonth $lunarMonth): ?static
    {
        return $lunarMonth->isLeap() ? null : new static($lunarMonth->getMonth() - 1);
    }

    function next(int $n): static
    {
        return self::fromIndex($this->nextIndex($n));
    }
}
