<?php

namespace com\tyme\culture\star\twentyeight;


use com\tyme\culture\Animal;
use com\tyme\culture\Land;
use com\tyme\culture\Luck;
use com\tyme\culture\star\seven\SevenStar;
use com\tyme\culture\Zone;
use com\tyme\LoopTyme;

/**
 * 二十八宿
 * @author 6tail
 * @package com\tyme\culture\star\twentyeight
 */
class TwentyEightStar extends LoopTyme
{
    static array $NAMES = ['角', '亢', '氐', '房', '心', '尾', '箕', '斗', '牛', '女', '虚', '危', '室', '壁', '奎', '娄', '胃', '昴', '毕', '觜', '参', '井', '鬼', '柳', '星', '张', '翼', '轸'];

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

    /**
     * 七曜
     *
     * @return SevenStar 七曜
     */
    function getSevenStar(): SevenStar
    {
        return SevenStar::fromIndex($this->index % 7 + 4);
    }

    /**
     * 九野
     *
     * @return Land 九野
     */
    function getLand(): Land
    {
        return Land::fromIndex([4, 4, 4, 2, 2, 2, 7, 7, 7, 0, 0, 0, 0, 5, 5, 5, 6, 6, 6, 1, 1, 1, 8, 8, 8, 3, 3, 3][$this->index]);
    }

    /**
     * 宫
     *
     * @return Zone 宫
     */
    function getZone(): Zone
    {
        return Zone::fromIndex(intdiv($this->index, 7));
    }

    /**
     * 动物
     *
     * @return Animal 动物
     */
    function getAnimal(): Animal
    {
        return Animal::fromIndex($this->index);
    }

    /**
     * 吉凶
     *
     * @return Luck 吉凶
     */
    function getLuck(): Luck
    {
        return Luck::fromIndex([0, 1, 1, 0, 1, 0, 0, 0, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 1, 1, 1, 0, 1, 0][$this->index]);
    }

}
