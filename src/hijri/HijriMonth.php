<?php

namespace com\tyme\hijri;

use com\tyme\unit\MonthUnit;

/**
 * 回历月
 * @author 6tail
 * @package com\tyme\hijri
 */
class HijriMonth extends MonthUnit
{

    static array $NAMES = ['穆哈兰姆月', '色法尔月', '赖比尔·敖外鲁月', '赖比尔·阿色尼月', '主马达·敖外鲁月', '主马达·阿色尼月', '赖哲卜月', '舍尔邦月', '赖买丹月', '闪瓦鲁月', '都尔喀尔德月', '都尔黑哲月'];

    protected function __construct(int $year, int $month)
    {
        self::validate($year, $month);
        parent::__construct($year, $month);
    }

    static function validate(int $year, int $month): void
    {
        parent::validateRange($month, 1, 12, 'hijri month');
        HijriYear::validate($year);
    }

    static function fromYm(int $year, int $month): static
    {
        return new static($year, $month);
    }

    /**
     * 回历年
     * @return HijriYear 回历年
     */
    function getHijriYear(): HijriYear
    {
        return HijriYear::fromYear($this->year);
    }

    /**
     * 天数（单数月30天，双数月29天，闰年第12月30天)
     * @return int 天数
     */
    function getDayCount(): int
    {
        $d = ($this->month % 2 == 0) ? 29 : 30;
        if ($this->month == 12 && $this->getHijriYear()->isLeap()) {
            $d++;
        }
        return $d;
    }

    /**
     * 位于当年的索引(0-11)
     * @return int 索引
     */
    function getIndexInYear(): int
    {
        return $this->month - 1;
    }

    function getName(): string
    {
        return static::$NAMES[$this->getIndexInYear()];
    }

    function next(int $n): static
    {
        $i = $this->month - 1 + $n;
        return static::fromYm(intdiv($this->year * 12 + $i, 12), $this->indexOf($i, null, 12) + 1);
    }

    /**
     * 本月的回历日列表
     * @return HijriDay[] 回历日列表
     */
    function getDays(): array
    {
        $size = $this->getDayCount();
        $l = array();
        for ($i = 1; $i <= $size; $i++) {
            $l[] = HijriDay::fromYmd($this->year, $this->month, $i);
        }
        return $l;
    }

    /**
     * 首日
     * @return HijriDay 回历日
     */
    function getFirstDay(): HijriDay
    {
        return HijriDay::fromYmd($this->year, $this->month, 1);
    }

    function __toString(): string
    {
        return $this->getHijriYear() . $this->getName();
    }
}
