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
     * @var float 月
     */
    protected float $cursoryJulianDay;

    protected function __construct(int $year = null, int $index = null, string $name = null, float $cursoryJulianDay = null)
    {
        $idx = $index;
        if ($index !== null) {
            parent::__construct(self::$NAMES, $index);
        } else if ($name != null) {
            parent::__construct(self::$NAMES, null, $name);
            $idx = $this->index;
        }
        if ($year !== null) {
            $this->initByYear($year, $idx);
        } else if ($cursoryJulianDay !== null) {
            $this->cursoryJulianDay = $cursoryJulianDay;
        }
    }

    protected function initByYear(int $year, int $offset): void
    {
        $jd = floor(($year - 2000) * 365.2422 + 180);
        // 355是2000.12冬至，得到较靠近jd的冬至估计值
        $w = floor(($jd - 355 + 183) / 365.2422) * 365.2422 + 355;
        if (ShouXingUtil::calcQi($w) > $jd) {
            $w -= 365.2422;
        }
        $this->cursoryJulianDay = ShouXingUtil::calcQi($w + 15.2184 * $offset);
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
        return new static(null, $this->nextIndex($n), null, $this->cursoryJulianDay + 15.2184 * $n);
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
     * 儒略日
     *
     * @return JulianDay 儒略日
     */
    function getJulianDay(): JulianDay
    {
        return JulianDay::fromJulianDay(ShouXingUtil::qiAccurate2($this->cursoryJulianDay) + JulianDay::J2000);
    }

    /**
     * 粗略的儒略日
     *
     * @return float 儒略日数
     */
    function getCursoryJulianDay(): float
    {
        return $this->cursoryJulianDay;
    }

}
