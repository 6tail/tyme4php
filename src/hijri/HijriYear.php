<?php

namespace com\tyme\hijri;

use com\tyme\unit\YearUnit;

/**
 * 回历年
 * @author 6tail
 * @package com\tyme\hijri
 */
class HijriYear extends YearUnit
{
    protected function __construct(int $year)
    {
        self::validate($year);
        parent::__construct($year);
    }

    static function validate(int $year): void
    {
        parent::validateRange($year, -640, 9666, 'hijri year');
    }

    static function fromYear(int $year): static
    {
        return new static($year);
    }

    /**
     * 天数（平年354，闰年355）
     * @return int 天数
     */
    function getDayCount(): int
    {
        return $this->isLeap() ? 355 : 354;
    }

    /**
     * 是否闰年（30年周期中第2、5、7、10、13、16、18、21、24、26、29年为闰年）
     * @return bool true/false
     */
    function isLeap(): bool
    {
        return in_array(((($this->year - 1) % 30) + 30) % 30, [1, 4, 6, 9, 12, 15, 17, 20, 23, 25, 28]);
    }

    function getName(): string
    {
        return $this->year . '年';
    }

    function next(int $n): static
    {
        return static::fromYear($this->year + $n);
    }

    /**
     * 月份列表，1年有12个月。
     * @return HijriMonth[] 回历月列表
     */
    function getMonths(): array
    {
        $l = array();
        for ($i = 1; $i <= 12; $i++) {
            $l[] = HijriMonth::fromYm($this->year, $i);
        }
        return $l;
    }

    /**
     * 首月
     * @return HijriMonth 回历月
     */
    function getFirstMonth(): HijriMonth
    {
        return HijriMonth::fromYm($this->year, 1);
    }

    function __toString(): string
    {
        return $this->getName();
    }
}
