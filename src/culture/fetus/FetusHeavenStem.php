<?php

namespace com\tyme\culture\fetus;


use com\tyme\LoopTyme;

/**
 * 天干六甲胎神（《天干六甲胎神歌》甲己之日占在门，乙庚碓磨休移动。丙辛厨灶莫相干，丁壬仓库忌修弄。戊癸房床若移整，犯之孕妇堕孩童。）
 * @author 6tail
 * @package com\tyme\culture\fetus
 */
class FetusHeavenStem extends LoopTyme
{
    static array $NAMES = ['门', '碓磨', '厨灶', '仓库', '房床'];

    function __construct(int $index)
    {
        parent::__construct(static::$NAMES, $index);
    }

    function next(int $n): static
    {
        return new static($this->nextIndex($n));
    }
}
