<?php

namespace com\tyme\sixtycycle;


use com\tyme\AbstractCulture;
use com\tyme\solar\SolarDay;
use com\tyme\solar\SolarTerm;

/**
 * 三柱（年柱、月柱、日柱）
 * @author 6tail
 * @package com\tyme\sixtycycle
 */
class ThreePillars extends AbstractCulture
{
    /**
     * @var SixtyCycle 年柱
     */
    protected SixtyCycle $year;

    /**
     * @var SixtyCycle 月柱
     */
    protected SixtyCycle $month;

    /**
     * @var SixtyCycle 日柱
     */
    protected SixtyCycle $day;

    function __construct(SixtyCycle|string $year, SixtyCycle|string $month, SixtyCycle|string $day)
    {
        $this->year = $year instanceof SixtyCycle ? $year : SixtyCycle::fromName($year);
        $this->month = $month instanceof SixtyCycle ? $month : SixtyCycle::fromName($month);
        $this->day = $day instanceof SixtyCycle ? $day : SixtyCycle::fromName($day);
    }

    /**
     * 年柱
     *
     * @return SixtyCycle 年柱
     */
    function getYear(): SixtyCycle
    {
        return $this->year;
    }

    /**
     * 月柱
     *
     * @return SixtyCycle 月柱
     */
    function getMonth(): SixtyCycle
    {
        return $this->month;
    }

    /**
     * 日柱
     *
     * @return SixtyCycle 日柱
     */
    function getDay(): SixtyCycle
    {
        return $this->day;
    }

    function getName(): string
    {
        return sprintf('%s %s %s', $this->year, $this->month, $this->day);
    }

    /**
     * 公历日列表
     * @param int $startYear 开始年(含)，支持1-9999年
     * @param int $endYear 结束年(含)，支持1-9999年
     * @return SolarDay[] 公历日列表
     */
    function getSolarDays(int $startYear, int $endYear): array
    {
        $l = array();
        // 月地支距寅月的偏移值
        $m = $this->month->getEarthBranch()->next(-2)->getIndex();
        // 月天干要一致
        if (!HeavenStem::fromIndex(($this->year->getHeavenStem()->getIndex() + 1) * 2 + $m)->equals($this->month->getHeavenStem())) {
            return $l;
        }
        // 1年的立春是辛酉，序号57
        $y = $this->year->next(-57)->getIndex() + 1;
        // 节令偏移值
        $m *= 2;
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
            $solarDay = $term->getSolarDay();
            if ($solarDay->getYear() >= $startYear) {
                // 日干支和节令干支的偏移值
                $d = $this->day->next(-$solarDay->getLunarDay()->getSixtyCycle()->getIndex())->getIndex();
                if ($d > 0) {
                    // 从节令推移天数
                    $solarDay = $solarDay->next($d);
                }
                // 验证一下
                if ($solarDay->getSixtyCycleDay()->getThreePillars()->equals($this)) {
                    $l[] = $solarDay;
                }
            }
            $y += 60;
        }
        return $l;
    }

}
