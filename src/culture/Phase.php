<?php

namespace com\tyme\culture;


use com\tyme\jd\JulianDay;
use com\tyme\LoopTyme;
use com\tyme\lunar\LunarDay;
use com\tyme\lunar\LunarMonth;
use com\tyme\solar\SolarDay;
use com\tyme\solar\SolarTime;
use com\tyme\util\ShouXingUtil;

/**
 * 月相
 * @author 6tail
 * @package com\tyme\culture
 */
class Phase extends LoopTyme
{
    static array $NAMES = ['新月', '蛾眉月', '上弦月', '盈凸月', '满月', '亏凸月', '下弦月', '残月'];

    /**
     * @var int 农历年
     */
    protected int $lunarYear;

    /**
     * @var int 农历月
     */
    protected int $lunarMonth;

    protected function __construct(int $lunarYear, int $lunarMonth, ?int $index = null, ?string $name = null)
    {
        if ($index !== null) {
            parent::__construct(static::$NAMES, $index);
            $m = LunarMonth::fromYm($lunarYear, $lunarMonth)->next(intdiv($index, $this->getSize()));
            $this->lunarYear = $m->getYear();
            $this->lunarMonth = $m->getMonth();
        } else if ($name !== null) {
            parent::__construct(static::$NAMES, null, $name);
            $this->lunarYear = $lunarYear;
            $this->lunarMonth = $lunarMonth;
        }
    }

    static function fromIndex(int $lunarYear, int $lunarMonth, int $index): static
    {
        return new static($lunarYear, $lunarMonth, $index);
    }

    static function fromName(int $lunarYear, int $lunarMonth, string $name): static
    {
        return new static($lunarYear, $lunarMonth, null, $name);
    }

    function next(int $n): static
    {
        $size = $this->getSize();
        $i = $this->index + $n;
        if ($i < 0) {
            $i -= $size;
        }
        $i = intdiv($i, $size);
        $m = LunarMonth::fromYm($this->lunarYear, $this->lunarMonth);
        if ($i != 0) {
            $m = $m->next($i);
        }
        return static::fromIndex($m->getYear(), $m->getMonth(), $this->nextIndex($n));
    }

    function getStartSolarTime(): SolarTime
    {
        $n = (int) floor(($this->lunarYear - 2000) * 365.2422 / 29.53058886);
        $i = 0;
        $p = M_PI * 2;
        $jd = JulianDay::J2000 + ShouXingUtil::ONE_THIRD;
        $d = LunarDay::fromYmd($this->lunarYear, $this->lunarMonth, 1)->getSolarDay();
        while (true) {
            $t = ShouXingUtil::msaLonT(($n + $i) * $p) * 36525;
            if (!JulianDay::fromJulianDay($jd + $t - ShouXingUtil::dtT($t))->getSolarDay()->isBefore($d)) {
                break;
            }
            $i++;
        }
        $r = [0, 90, 180, 270];
        $t = ShouXingUtil::msaLonT(($n + $i + $r[intdiv($this->index, 2)] / 360.0) * $p) * 36525;
        return JulianDay::fromJulianDay($jd + $t - ShouXingUtil::dtT($t))->getSolarTime();
    }

    function getSolarTime(): SolarTime
    {
        $t = $this->getStartSolarTime();
        return $this->index % 2 == 1 ? $t->next(1) : $t;
    }

    function getSolarDay(): SolarDay
    {
        $d = $this->getStartSolarTime()->getSolarDay();
        return $this->index % 2 == 1 ? $d->next(1) : $d;
    }
}
