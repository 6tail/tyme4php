<?php

namespace com\tyme\culture\fetus;


use com\tyme\AbstractCulture;
use com\tyme\culture\Direction;
use com\tyme\enums\Side;
use com\tyme\lunar\LunarDay;

/**
 * 逐日胎神
 * @author 6tail
 * @package com\tyme\culture\fetus
 */
class FetusDay extends AbstractCulture
{
    /**
     * @var FetusHeavenStem 天干六甲胎神
     */
    protected FetusHeavenStem $fetusHeavenStem;

    /**
     * @var FetusEarthBranch 地支六甲胎神
     */
    protected FetusEarthBranch $fetusEarthBranch;

    /**
     * @var Side 内外
     */
    protected Side $side;

    /**
     * @var Direction 方位
     */
    protected Direction $direction;

    protected function __construct(LunarDay $lunarDay)
    {
        $sixtyCycle = $lunarDay->getSixtyCycle();
        $this->fetusHeavenStem = new FetusHeavenStem($sixtyCycle->getHeavenStem()->getIndex() % 5);
        $this->fetusEarthBranch = new FetusEarthBranch($sixtyCycle->getEarthBranch()->getIndex() % 6);
        $index = [3, 3, 8, 8, 8, 8, 8, 1, 1, 1, 1, 1, 1, 6, 6, 6, 6, 6, 5, 5, 5, 5, 5, 5, 0, 0, 0, 0, 0, -9, -9, -9, -9, -9, -5, -5, -1, -1, -1, -3, -7, -7, -7, -7, -5, 7, 7, 7, 7, 7, 7, 2, 2, 2, 2, 2, 3, 3, 3, 3][$sixtyCycle->getIndex()];
        $this->side = Side::fromCode($index < 0 ? 0 : 1);
        $this->direction = Direction::fromIndex($index);
    }

    static function fromLunarDay(LunarDay $lunarDay): static
    {
        return new static($lunarDay);
    }

    function getName(): string
    {
        $s = $this->fetusHeavenStem->getName() . $this->fetusEarthBranch->getName();
        if ('门门' == $s) {
            $s = '占大门';
        } else if ('碓磨碓' == $s) {
            $s = '占碓磨';
        } else if ('房床床' == $s) {
            $s = '占房床';
        } else if (str_starts_with($s, '门')) {
            $s = '占' . $s;
        }

        $s .= ' ';

        $directionName = $this->direction->getName();
        if (Side::IN == $this->side) {
            $s .= '房';
        }
        $s .= $this->side->getName();

        if (Side::OUT == $this->side && str_contains('北南西东', $directionName)) {
            $s .= '正';
        }
        $s .= $directionName;
        return $s;
    }

    /**
     * 内外
     *
     * @return Side 内外
     */
    function getSide(): Side
    {
        return $this->side;
    }

    /**
     * 方位
     *
     * @return Direction 方位
     */
    function getDirection(): Direction
    {
        return $this->direction;
    }

    /**
     * 天干六甲胎神
     *
     * @return FetusHeavenStem 天干六甲胎神
     */
    function getFetusHeavenStem(): FetusHeavenStem
    {
        return $this->fetusHeavenStem;
    }

    /**
     * 地支六甲胎神
     *
     * @return FetusEarthBranch 地支六甲胎神
     */
    function getFetusEarthBranch(): FetusEarthBranch
    {
        return $this->fetusEarthBranch;
    }
}
