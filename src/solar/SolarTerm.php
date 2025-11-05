<?php

namespace com\tyme\solar;


use com\tyme\jd\JulianDay;
use com\tyme\LoopTyme;
use com\tyme\util\ShouXingUtil;

/**
 * 节气
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarTerm extends LoopTyme
{
    static array $NAMES = ['冬至', '小寒', '大寒', '立春', '雨水', '惊蛰', '春分', '清明', '谷雨', '立夏', '小满', '芒种', '夏至', '小暑', '大暑', '立秋', '处暑', '白露', '秋分', '寒露', '霜降', '立冬', '小雪', '大雪'];

    /**
     * @var int 年
     */
    protected int $year;

    /**
     * @var float 儒略日（用于日历，只精确到日中午12:00）
     */
    protected float $cursoryJulianDay;

    protected function __construct(int $year, ?int $index = null, ?string $name = null)
    {
        $y = $year;
        if ($index !== null) {
            parent::__construct(static::$NAMES, $index);
            $size = count(static::$NAMES);
            $y = intdiv($year * $size + $index, $size);
        } else if ($name != null) {
            parent::__construct(static::$NAMES, null, $name);
        }

        $jd = floor(($y - 2000) * 365.2422 + 180);
        // 355是2000.12冬至，得到较靠近jd的冬至估计值
        $w = floor(($jd - 355 + 183) / 365.2422) * 365.2422 + 355;
        if (ShouXingUtil::calcQi($w) > $jd) {
            $w -= 365.2422;
        }
        $this->year = $y;
        $this->cursoryJulianDay = ShouXingUtil::calcQi($w + 15.2184 * $this->index);
    }

    static function fromIndex(int $year, int $index): static
    {
        return new static($year, $index);
    }

    static function fromName(int $year, string $name): static
    {
        return new static($year, null, $name);
    }

    function next(int $n): SolarTerm
    {
        $size = $this->getSize();
        $i = $this->index + $n;
        return static::fromIndex(intdiv($this->year * $size + $i, $size), $this->indexOf($i));
    }

    /**
     * 是否节令
     *
     * @return bool true/false
     */
    function isJie(): bool
    {
        return $this->index % 2 == 1;
    }

    /**
     * 是否气令
     *
     * @return bool true/false
     */
    function isQi(): bool
    {
        return $this->index % 2 == 0;
    }

    /**
     * 儒略日（精确到秒）
     *
     * @return JulianDay 儒略日
     */
    function getJulianDay(): JulianDay
    {
        return JulianDay::fromJulianDay(ShouXingUtil::qiAccurate2($this->cursoryJulianDay) + JulianDay::J2000);
    }

    /**
     * 公历日（用于日历）
     *
     * @return SolarDay 公历日
     */
    function getSolarDay(): SolarDay
    {
        return JulianDay::fromJulianDay($this->cursoryJulianDay + JulianDay::J2000)->getSolarDay();
    }

    /**
     * 年
     *
     * @return int 年
     */
    function getYear(): int
    {
        return $this->year;
    }

    /**
     * 儒略日（用于日历，只精确到日中午12:00）
     *
     * @return float 儒略日数
     */
    function getCursoryJulianDay(): float
    {
        return $this->cursoryJulianDay;
    }

}
