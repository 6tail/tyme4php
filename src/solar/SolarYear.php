<?php

namespace com\tyme\solar;


use com\tyme\AbstractTyme;
use InvalidArgumentException;

/**
 * 公历年
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarYear extends AbstractTyme
{
    /**
     * @var int 年
     */
    protected int $year;

    protected function __construct(int $year)
    {
        if ($year < 1 || $year > 9999) {
            throw new InvalidArgumentException(sprintf('illegal solar year: %d', $year));
        }
        $this->year = $year;
    }

    static function fromYear(int $year): static
    {
        return new static($year);
    }

    /**
     * 年
     * @return int 年
     */
    function getYear(): int
    {
        return $this->year;
    }

    /**
     * 天数（1582年355天，平年365天，闰年366天）
     *
     * @return int 天数
     */
    function getDayCount(): int
    {
        if (1582 == $this->year) {
            return 355;
        }
        return $this->isLeap() ? 366 : 365;
    }

    /**
     * 是否闰年(1582年以前，使用儒略历，能被4整除即为闰年。以后采用格里历，四年一闰，百年不闰，四百年再闰。)
     *
     * @return bool true/false
     */
    function isLeap(): bool
    {
        if ($this->year < 1600) {
            return $this->year % 4 == 0;
        }
        return ($this->year % 4 == 0 && $this->year % 100 != 0) || ($this->year % 400 == 0);
    }

    function getName(): string
    {
        return sprintf('%d年', $this->year);
    }

    function next(int $n): static
    {
        return static::fromYear($this->year + $n);
    }

    /**
     * 月份列表
     *
     * @return SolarMonth[] 月份列表，1年有12个月。
     */
    function getMonths(): array
    {
        $l = array();
        for ($i = 1; $i < 13; $i++) {
            $l[] = SolarMonth::fromYm($this->year, $i);
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
        for ($i = 0; $i < 4; $i++) {
            $l[] = SolarSeason::fromIndex($this->year, $i);
        }
        return $l;
    }

    /**
     * 半年列表
     *
     * @return SolarHalfYear[] 半年列表，1年有2个半年。
     */
    function getHalfYears(): array
    {
        $l = array();
        for ($i = 0; $i < 2; $i++) {
            $l[] = SolarHalfYear::fromIndex($this->year, $i);
        }
        return $l;
    }

}
