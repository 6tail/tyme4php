<?php

namespace com\tyme\solar;


use com\tyme\AbstractTyme;
use com\tyme\culture\Constellation;
use com\tyme\culture\dog\Dog;
use com\tyme\culture\dog\DogDay;
use com\tyme\culture\nine\Nine;
use com\tyme\culture\nine\NineDay;
use com\tyme\culture\phenology\Phenology;
use com\tyme\culture\phenology\PhenologyDay;
use com\tyme\culture\plumrain\PlumRain;
use com\tyme\culture\plumrain\PlumRainDay;
use com\tyme\culture\Week;
use com\tyme\festival\SolarFestival;
use com\tyme\holiday\LegalHoliday;
use com\tyme\jd\JulianDay;
use com\tyme\lunar\LunarDay;
use com\tyme\lunar\LunarMonth;
use InvalidArgumentException;

/**
 * 公历日
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarDay extends AbstractTyme
{
    static array $NAMES = ['1日', '2日', '3日', '4日', '5日', '6日', '7日', '8日', '9日', '10日', '11日', '12日', '13日', '14日', '15日', '16日', '17日', '18日', '19日', '20日', '21日', '22日', '23日', '24日', '25日', '26日', '27日', '28日', '29日', '30日', '31日'];

    /**
     * @var SolarMonth 公历月
     */
    protected SolarMonth $month;

    /**
     * @var int 日
     */
    protected int $day;

    protected function __construct(int $year, int $month, int $day)
    {
        $m = SolarMonth::fromYm($year, $month);
        if ($day < 1) {
            throw new InvalidArgumentException(sprintf('illegal solar day: %d-%d-%d', $year, $month, $day));
        }
        if (1582 == $year && 10 == $month) {
            if (($day > 4 && $day < 15) || $day > 31) {
                throw new InvalidArgumentException(sprintf('illegal solar day: %d-%d-%d', $year, $month, $day));
            }
        } else if ($day > $m->getDayCount()) {
            throw new InvalidArgumentException(sprintf('illegal solar day: %d-%d-%d', $year, $month, $day));
        }
        $this->month = $m;
        $this->day = $day;
    }

    static function fromYmd(int $year, int $month, int $day): static
    {
        return new static($year, $month, $day);
    }

    /**
     * 公历月
     *
     * @return SolarMonth 公历月
     */
    function getSolarMonth(): SolarMonth
    {
        return $this->month;
    }

    /**
     * 年
     *
     * @return int 年
     */
    function getYear(): int
    {
        return $this->month->getYear();
    }

    /**
     * 月
     *
     * @return int 月
     */
    function getMonth(): int
    {
        return $this->month->getMonth();
    }

    /**
     * 日
     *
     * @return int 日
     */
    function getDay(): int
    {
        return $this->day;
    }

    /**
     * 星期
     *
     * @return Week 星期
     */
    function getWeek(): Week
    {
        return $this->getJulianDay()->getWeek();
    }

    /**
     * 星座
     *
     * @return Constellation 星座
     */
    function getConstellation(): Constellation
    {
        $index = 11;
        $y = $this->getMonth() * 100 + $this->day;
        if ($y >= 321 && $y <= 419) {
            $index = 0;
        } else if ($y >= 420 && $y <= 520) {
            $index = 1;
        } else if ($y >= 521 && $y <= 621) {
            $index = 2;
        } else if ($y >= 622 && $y <= 722) {
            $index = 3;
        } else if ($y >= 723 && $y <= 822) {
            $index = 4;
        } else if ($y >= 823 && $y <= 922) {
            $index = 5;
        } else if ($y >= 923 && $y <= 1023) {
            $index = 6;
        } else if ($y >= 1024 && $y <= 1122) {
            $index = 7;
        } else if ($y >= 1123 && $y <= 1221) {
            $index = 8;
        } else if ($y >= 1222 || $y <= 119) {
            $index = 9;
        } else if ($y <= 218) {
            $index = 10;
        }
        return Constellation::fromIndex($index);
    }

    function getName(): string
    {
        return self::$NAMES[$this->day - 1];
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->month, $this->getName());
    }

    function next(int $n): SolarDay
    {
        return $this->getJulianDay()->next($n)->getSolarDay();
    }

    /**
     * 是否在指定公历日之前
     *
     * @param SolarDay $target 公历日
     * @return bool true/false
     */
    function isBefore(SolarDay $target): bool
    {
        $aYear = $this->getYear();
        $bYear = $target->getYear();
        if ($aYear != $bYear) {
            return $aYear < $bYear;
        }
        $aMonth = $this->getMonth();
        $bMonth = $target->getMonth();
        return $aMonth != $bMonth ? $aMonth < $bMonth : $this->day < $target->getDay();
    }

    /**
     * 是否在指定公历日之后
     *
     * @param SolarDay $target 公历日
     * @return bool true/false
     */
    function isAfter(SolarDay $target): bool
    {
        $aYear = $this->getYear();
        $bYear = $target->getYear();
        if ($aYear != $bYear) {
            return $aYear > $bYear;
        }
        $aMonth = $this->getMonth();
        $bMonth = $target->getMonth();
        return $aMonth != $bMonth ? $aMonth > $bMonth : $this->day > $target->getDay();
    }

    /**
     * 节气
     *
     * @return SolarTerm 节气
     */
    function getTerm(): SolarTerm
    {
        return $this->getTermDay()->getSolarTerm();
    }

    /**
     * 节气第几天
     *
     * @return SolarTermDay 节气第几天
     */
    function getTermDay(): SolarTermDay
    {
        $y = $this->getYear();
        $i = $this->getMonth() * 2;
        if ($i == 24) {
            $y += 1;
            $i = 0;
        }
        $term = SolarTerm::fromIndex($y, $i);
        $day = $term->getJulianDay()->getSolarDay();
        while ($this->isBefore($day)) {
            $term = $term->next(-1);
            $day = $term->getJulianDay()->getSolarDay();
        }
        return new SolarTermDay($term, $this->subtract($day));
    }

    /**
     * 公历周
     *
     * @param int $start 起始星期，1234560分别代表星期一至星期天
     * @return SolarWeek 公历周
     */
    function getSolarWeek(int $start): SolarWeek
    {
        $y = $this->getYear();
        $m = $this->getMonth();
        return SolarWeek::fromYm($y, $m, (int)ceil(($this->day + SolarDay::fromYmd($y, $m, 1)->getWeek()->next(-$start)->getIndex()) / 7.0) - 1, $start);
    }

    /**
     * 七十二候
     *
     * @return PhenologyDay 七十二候
     */
    function getPhenologyDay(): PhenologyDay
    {
        $term = $this->getTerm();
        $dayIndex = $this->subtract($term->getJulianDay()->getSolarDay());
        $index = intdiv($dayIndex, 5);
        if ($index > 2) {
            $index = 2;
        }
        $dayIndex -= $index * 5;
        return new PhenologyDay(Phenology::fromIndex($term->getIndex() * 3 + $index), $dayIndex);
    }

    /**
     * 三伏天
     *
     * @return DogDay|null 三伏天
     */
    function getDogDay(): ?DogDay
    {
        $xiaZhi = SolarTerm::fromIndex($this->getYear(), 12);
        // 第1个庚日
        $start = $xiaZhi->getJulianDay()->getSolarDay();
        $add = 6 - $start->getLunarDay()->getSixtyCycle()->getHeavenStem()->getIndex();
        if ($add < 0) {
            $add += 10;
        }
        // 第3个庚日，即初伏第1天
        $add += 20;
        $start = $start->next($add);
        $days = $this->subtract($start);
        // 初伏以前
        if ($days < 0) {
            return null;
        }
        if ($days < 10) {
            return new DogDay(Dog::fromIndex(0), $days);
        }
        // 第4个庚日，中伏第1天
        $start = $start->next(10);
        $days = $this->subtract($start);
        if ($days < 10) {
            return new DogDay(Dog::fromIndex(1), $days);
        }
        // 第5个庚日，中伏第11天或末伏第1天
        $start = $start->next(10);
        $days = $this->subtract($start);
        // 立秋
        if ($xiaZhi->next(3)->getJulianDay()->getSolarDay()->isAfter($start)) {
            if ($days < 10) {
                return new DogDay(Dog::fromIndex(1), $days + 10);
            }
            $start = $start->next(10);
            $days = $this->subtract($start);
        }
        if ($days < 10) {
            return new DogDay(Dog::fromIndex(2), $days);
        }
        return null;
    }

    /**
     * 数九天
     *
     * @return NineDay|null 数九天
     */
    function getNineDay(): ?NineDay
    {
        $year = $this->getYear();
        $start = SolarTerm::fromIndex($year + 1, 0)->getJulianDay()->getSolarDay();
        if ($this->isBefore($start)) {
            $start = SolarTerm::fromIndex($year, 0)->getJulianDay()->getSolarDay();
        }
        $end = $start->next(81);
        if ($this->isBefore($start) || !$this->isBefore($end)) {
            return null;
        }
        $days = $this->subtract($start);
        return new NineDay(Nine::fromIndex(intdiv($days, 9)), $days % 9);
    }

    /**
     * 梅雨天（芒种后的第1个丙日入梅，小暑后的第1个未日出梅）
     * @return PlumRainDay|null 梅雨天
     */
    function getPlumRainDay(): ?PlumRainDay
    {
        // 芒种
        $grainInEar = SolarTerm::fromIndex($this->getYear(), 11);
        $start = $grainInEar->getJulianDay()->getSolarDay();
        $add = 2 - $start->getLunarDay()->getSixtyCycle()->getHeavenStem()->getIndex();
        if ($add < 0) {
            $add += 10;
        }
        // 芒种后的第1个丙日
        $start = $start->next($add);

        // 小暑
        $slightHeat = $grainInEar->next(2);
        $end = $slightHeat->getJulianDay()->getSolarDay();
        $add = 7 - $end->getLunarDay()->getSixtyCycle()->getEarthBranch()->getIndex();
        if ($add < 0) {
            $add += 12;
        }
        // 小暑后的第1个未日
        $end = $end->next($add);

        if ($this->isBefore($start) || $this->isAfter($end)) {
            return null;
        }
        return $this->equals($end) ? new PlumRainDay(PlumRain::fromIndex(1), 0) : new PlumRainDay(PlumRain::fromIndex(0), $this->subtract($start));
    }

    /**
     * 位于当年的索引
     *
     * @return int 索引
     */
    function getIndexInYear(): int
    {
        return $this->subtract(self::fromYmd($this->getYear(), 1, 1));
    }

    /**
     * 公历日期相减，获得相差天数
     *
     * @param SolarDay $target 公历
     * @return int 天数
     */
    function subtract(SolarDay $target): int
    {
        return (int)($this->getJulianDay()->subtract($target->getJulianDay()));
    }

    /**
     * 儒略日
     *
     * @return JulianDay 儒略日
     */
    function getJulianDay(): JulianDay
    {
        return JulianDay::fromYmdHms($this->getYear(), $this->getMonth(), $this->day, 0, 0, 0);
    }

    /**
     * 农历日
     *
     * @return LunarDay 农历日
     */
    function getLunarDay(): LunarDay
    {
        $m = LunarMonth::fromYm($this->getYear(), $this->getMonth());
        $days = $this->subtract($m->getFirstJulianDay()->getSolarDay());
        while ($days < 0) {
            $m = $m->next(-1);
            $days += $m->getDayCount();
        }
        return LunarDay::fromYmd($m->getYear(), $m->getMonthWithLeap(), $days + 1);
    }

    /**
     * 法定假日，如果当天不是法定假日，返回null
     *
     * @return ?LegalHoliday 法定假日
     */
    function getLegalHoliday(): ?LegalHoliday
    {
        return LegalHoliday::fromYmd($this->getYear(), $this->getMonth(), $this->day);
    }

    /**
     * 公历现代节日，如果当天不是公历现代节日，返回null
     *
     * @return ?SolarFestival 公历现代节日
     */
    function getFestival(): ?SolarFestival
    {
        return SolarFestival::fromYmd($this->getYear(), $this->getMonth(), $this->day);
    }

}
