<?php

namespace com\tyme\culture\fetus;


use com\tyme\LoopTyme;

/**
 * 地支六甲胎神（《地支六甲胎神歌》子午二日碓须忌，丑未厕道莫修移。寅申火炉休要动，卯酉大门修当避。辰戌鸡栖巳亥床，犯着六甲身堕胎。）
 * @author 6tail
 * @package com\tyme\culture\fetus
 */
class FetusEarthBranch extends LoopTyme
{
    static array $NAMES = ['碓', '厕', '炉', '门', '栖', '床'];

    function __construct(int $index)
    {
        parent::__construct(self::$NAMES, $index);
    }

    function next(int $n): static
    {
        return new static($this->nextIndex($n));
    }
}
