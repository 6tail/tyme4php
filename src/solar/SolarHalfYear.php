<?php

namespace com\tyme\solar;


use com\tyme\AbstractTyme;
use InvalidArgumentException;

/**
 * 公历半年
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarHalfYear extends AbstractTyme
{
    static array $NAMES = ['上半年', '下半年'];

    /**
     * @var SolarYear 年
     */
    protected SolarYear $year;

    /**
     * @var int 索引，0-1
     */
    protected int $index;

    protected function __construct(int $year, int $index)
    {
        if ($index < 0 || $index > 1) {
            throw new InvalidArgumentException(sprintf('illegal solar half year index: %d', $index));
        }
        $this->year = SolarYear::fromYear($year);
        $this->index = $index;
    }

    static function fromIndex(int $year, int $index): static
    {
        return new static($year, $index);
    }

    /**
     * 公历年
     * @return SolarYear 公历年
     */
    function getSolarYear(): SolarYear
    {
        return $this->year;
    }

    /**
     * 年
     * @return int 年
     */
    function getYear(): int
    {
        return $this->year->getYear();
    }

    /**
     * 索引
     *
     * @return int 索引，0-1
     */
    function getIndex(): int
    {
        return $this->index;
    }

    function getName(): string
    {
        return self::$NAMES[$this->index];
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->year, $this->getName());
    }

    function next(int $n): static
    {
        if ($n == 0) {
            return self::fromIndex($this->getYear(), $this->index);
        }
        $i = $this->index + $n;
        $y = $this->getYear() + intdiv($i, 2);
        $i %= 2;
        if ($i < 0) {
            $i += 2;
            $y -= 1;
        }
        return self::fromIndex($y, $i);
    }

    /**
     * 月份列表
     *
     * @return SolarMonth[] 月份列表，1年有12个月。
     */
    function getMonths(): array
    {
        $l = array();
        $y = $this->getYear();
        for ($i = 0; $i < 6; $i++) {
            $l[] = SolarMonth::fromYm($y, $this->index * 6 + $i + 1);
        }
        return $l;
    }

    /**
     * 季度列表
     *
     * @return SolarSeason[] 季度列表，1年有4个季度。
     */
    function getSeasons(): array
    {
        $l = array();
        $y = $this->getYear();
        for ($i = 0; $i < 2; $i++) {
            $l[] = SolarSeason::fromIndex($y, $this->index * 2 + $i);
        }
        return $l;
    }

}
