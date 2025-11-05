<?php

namespace com\tyme\eightchar;


use com\tyme\AbstractCulture;
use com\tyme\culture\Duty;
use com\tyme\sixtycycle\EarthBranch;
use com\tyme\sixtycycle\HeavenStem;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\sixtycycle\SixtyCycleDay;
use com\tyme\sixtycycle\ThreePillars;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;

/**
 * 八字
 *
 * @author 6tail
 * @package com\tyme\eightchar
 */
class EightChar extends AbstractCulture
{
    /**
     * @var ThreePillars 三柱
     */
    protected ThreePillars $threePillars;

    /**
     * @var SixtyCycle 时柱
     */
    protected SixtyCycle $hour;

    function __construct(SixtyCycle|string $year, SixtyCycle|string $month, SixtyCycle|string $day, SixtyCycle|string $hour)
    {
        $this->threePillars = new ThreePillars($year, $month, $day);
        $this->hour = $hour instanceof SixtyCycle ? $hour : SixtyCycle::fromName($hour);
    }

    /**
     * 年柱
     *
     * @return SixtyCycle 年柱
     */
    function getYear(): SixtyCycle
    {
        return $this->threePillars->getYear();
    }

    /**
     * 月柱
     *
     * @return SixtyCycle 月柱
     */
    function getMonth(): SixtyCycle
    {
        return $this->threePillars->getMonth();
    }

    /**
     * 日柱
     *
     * @return SixtyCycle 日柱
     */
    function getDay(): SixtyCycle
    {
        return $this->threePillars->getDay();
    }

    /**
     * 时柱
     *
     * @return SixtyCycle 时柱
     */
    function getHour(): SixtyCycle
    {
        return $this->hour;
    }

    /**
     * 胎元
     *
     * @return SixtyCycle 胎元
     */
    function getFetalOrigin(): SixtyCycle
    {
        $m = $this->getMonth();
        return SixtyCycle::fromName(sprintf('%s%s', $m->getHeavenStem()->next(1)->getName(), $m->getEarthBranch()->next(3)->getName()));
    }

    /**
     * 胎息
     *
     * @return SixtyCycle 胎息
     */
    function getFetalBreath(): SixtyCycle
    {
        $d = $this->getDay();
        return SixtyCycle::fromName(sprintf('%s%s', $d->getHeavenStem()->next(5)->getName(), EarthBranch::fromIndex(13 - $d->getEarthBranch()->getIndex())->getName()));
    }

    /**
     * 命宫
     *
     * @return SixtyCycle 命宫
     */
    function getOwnSign(): SixtyCycle
    {
        $m = $this->getMonth()->getEarthBranch()->getIndex() - 1;
        if ($m < 1) {
            $m += 12;
        }
        $h = $this->hour->getEarthBranch()->getIndex() - 1;
        if ($h < 1) {
            $h += 12;
        }
        $offset = $m + $h;
        $offset = ($offset >= 14 ? 26 : 14) - $offset;
        return SixtyCycle::fromName(sprintf('%s%s', HeavenStem::fromIndex(($this->getYear()->getHeavenStem()->getIndex() + 1) * 2 + $offset - 1)->getName(), EarthBranch::fromIndex($offset + 1)->getName()));
    }

    /**
     * 身宫
     *
     * @return SixtyCycle 身宫
     */
    function getBodySign(): SixtyCycle
    {
        $offset = $this->getMonth()->getEarthBranch()->getIndex() - 1;
        if ($offset < 1) {
            $offset += 12;
        }
        $offset += $this->hour->getEarthBranch()->getIndex() + 1;
        if ($offset > 12) {
            $offset -= 12;
        }
        return SixtyCycle::fromName(sprintf('%s%s', HeavenStem::fromIndex(($this->getYear()->getHeavenStem()->getIndex() + 1) * 2 + $offset - 1)->getName(), EarthBranch::fromIndex($offset + 1)->getName()));
    }

    /**
     * 建除十二值神
     *
     * @return Duty 建除十二值神
     * @deprecated
     * @see SixtyCycleDay
     */
    function getDuty(): Duty
    {
        return Duty::fromIndex($this->getDay()->getEarthBranch()->getIndex() - $this->getMonth()->getEarthBranch()->getIndex());
    }

    function getName(): string
    {
        return sprintf('%s %s', $this->threePillars, $this->hour);
    }

    /**
     * 公历时刻列表
     * @param int $startYear 开始年(含)，支持1-9999年
     * @param int $endYear 结束年(含)，支持1-9999年
     * @return SolarTime[] 公历时刻列表
     */
    function getSolarTimes(int $startYear, int $endYear): array
    {
        $l = array();
        $year = $this->getYear();
        $month = $this->getMonth();
        $day = $this->getDay();
        // 月地支距寅月的偏移值
        $m = $month->getEarthBranch()->next(-2)->getIndex();
        // 月天干要一致
        if (!HeavenStem::fromIndex(($year->getHeavenStem()->getIndex() + 1) * 2 + $m)->equals($month->getHeavenStem())) {
            return $l;
        }
        // 1年的立春是辛酉，序号57
        $y = $year->next(-57)->getIndex() + 1;
        // 节令偏移值
        $m *= 2;
        // 时辰地支转时刻
        $h = $this->hour->getEarthBranch()->getIndex() * 2;
        // 兼容子时多流派
        $hours = [$h];
        if ($h == 0) {
            $hours[] = 23;
        }
        $baseYear = $startYear - 1;
        if ($baseYear > $y) {
            $y += 60 * (int)ceil(($baseYear - $y) / 60.0);
        }
        while ($y <= $endYear) {
            // 立春为寅月的开始
            $term = SolarTerm::fromIndex($y, 3);
            // 节令推移，年干支和月干支就都匹配上了
            if ($m > 0) {
                $term = $term->next($m);
            }
            $solarTime = $term->getJulianDay()->getSolarTime();
            if ($solarTime->getYear() >= $startYear) {
                // 日干支和节令干支的偏移值
                $solarDay = $solarTime->getSolarDay();
                $d = $day->next(-$solarDay->getLunarDay()->getSixtyCycle()->getIndex())->getIndex();
                if ($d > 0) {
                    // 从节令推移天数
                    $solarDay = $solarDay->next($d);
                }
                foreach ($hours as $hour) {
                    $mi = 0;
                    $s = 0;
                    if ($d == 0 && $hour == $solarTime->getHour()) {
                        // 如果正好是节令当天，且小时和节令的小时数相等的极端情况，把分钟和秒钟带上
                        $mi = $solarTime->getMinute();
                        $s = $solarTime->getSecond();
                    }
                    $time = SolarTime::fromYmdHms($solarDay->getYear(), $solarDay->getMonth(), $solarDay->getDay(), $hour, $mi, $s);
                    if ($d == 30) {
                        $time = $time->next(-3600);
                    }
                    // 验证一下
                    if ($time->getLunarHour()->getEightChar()->equals($this)) {
                        $l[] = $time;
                    }
                }
            }
            $y += 60;
        }
        return $l;
    }

}
