<?php

namespace com\tyme\culture;


use com\tyme\LoopTyme;

/**
 * 月相
 * @author 6tail
 * @package com\tyme\culture
 */
class Phase extends LoopTyme
{
    static array $NAMES = ['朔月', '既朔月', '蛾眉新月', '蛾眉新月', '蛾眉月', '夕月', '上弦月', '上弦月', '九夜月', '宵月', '宵月', '宵月', '渐盈凸月', '小望月', '望月', '既望月', '立待月', '居待月', '寝待月', '更待月', '渐亏凸月', '下弦月', '下弦月', '有明月', '有明月', '蛾眉残月', '蛾眉残月', '残月', '晓月', '晦月'];

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
}
