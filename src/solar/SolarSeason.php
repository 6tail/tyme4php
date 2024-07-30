<?php

namespace com\tyme\solar;


use com\tyme\AbstractTyme;
use InvalidArgumentException;

/**
 * 公历季度
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarSeason extends AbstractTyme
{
    static array $NAMES = ['一季度', '二季度', '三季度', '四季度'];

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
        if ($index < 0 || $index > 3) {
            throw new InvalidArgumentException(sprintf('illegal solar season index: %d', $index));
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
        $y = $this->getYear() + intdiv($i, 4);
        $i %= 4;
        if ($i < 0) {
            $i += 4;
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
        for ($i = 1; $i < 4; $i++) {
            $l[] = SolarMonth::fromYm($y, $this->index * 3 + $i);
        }
        return $l;
    }

}
