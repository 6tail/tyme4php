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
     * 月
     *
     * @return SolarMonth 月
     */
    function getMonth(): SolarMonth
    {
        return $this->month;
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
        $y = $this->month->getMonth() * 100 + $this->day;
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
        $aYear = $this->month->getYear()->getYear();
        $targetMonth = $target->getMonth();
        $bYear = $targetMonth->getYear()->getYear();
        if ($aYear == $bYear) {
            $aMonth = $this->month->getMonth();
            $bMonth = $targetMonth->getMonth();
            return $aMonth == $bMonth ? $this->day < $target->getDay() : $aMonth < $bMonth;
        }
        return $aYear < $bYear;
    }

    /**
     * 是否在指定公历日之后
     *
     * @param SolarDay $target 公历日
     * @return bool true/false
     */
    function isAfter(SolarDay $target): bool
    {
        $aYear = $this->month->getYear()->getYear();
        $targetMonth = $target->getMonth();
        $bYear = $targetMonth->getYear()->getYear();
        if ($aYear == $bYear) {
            $aMonth = $this->month->getMonth();
            $bMonth = $targetMonth->getMonth();
            return $aMonth == $bMonth ? $this->day > $target->getDay() : $aMonth > $bMonth;
        }
        return $aYear > $bYear;
    }

    /**
     * 节气
     *
     * @return SolarTerm 节气
     */
    function getTerm(): SolarTerm
    {
        $term = SolarTerm::fromIndex($this->month->getYear()->getYear() + 1, 0);
        while ($this->isBefore($term->getJulianDay()->getSolarDay())) {
            $term = $term->next(-1);
        }
        return $term;
    }

    /**
     * 公历周
     *
     * @param int $start 起始星期，1234560分别代表星期一至星期天
     * @return SolarWeek 公历周
     */
    function getSolarWeek(int $start): SolarWeek
    {
        $y = $this->month->getYear()->getYear();
        $m = $this->month->getMonth();
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
        $xiaZhi = SolarTerm::fromIndex($this->month->getYear()->getYear(), 12);
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
        $year = $this->month->getYear()->getYear();
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
     * 位于当年的索引
     *
     * @return int 索引
     */
    function getIndexInYear(): int
    {
        $m = $this->month->getMonth();
        $y = $this->month->getYear()->getYear();
        $days = 0;
        for ($i = 1; $i < $m; $i++) {
            $days += SolarMonth::fromYm($y, $i)->getDayCount();
        }
        $d = $this->day;
        if (1582 == $y && 10 == $m) {
            if ($d >= 15) {
                $d -= 10;
            }
        }
        return $days + $d - 1;
    }

    /**
     * 公历日期相减，获得相差天数
     *
     * @param SolarDay $target 公历
     * @return int 天数
     */
    function subtract(SolarDay $target): int
    {
        return (int)($this->getJulianDay()->getDay() - $target->getJulianDay()->getDay());
    }

    /**
     * 儒略日
     *
     * @return JulianDay 儒略日
     */
    function getJulianDay(): JulianDay
    {
        return JulianDay::fromYmdHms($this->month->getYear()->getYear(), $this->month->getMonth(), $this->day, 0, 0, 0);
    }

    /**
     * 农历日
     *
     * @return LunarDay 农历日
     */
    function getLunarDay(): LunarDay
    {
        $m = LunarMonth::fromYm($this->month->getYear()->getYear(), $this->month->getMonth())->next(-3);
        $days = $this->subtract($m->getFirstJulianDay()->getSolarDay());
        while ($days >= $m->getDayCount()) {
            $m = $m->next(1);
            $days = $this->subtract($m->getFirstJulianDay()->getSolarDay());
        }
        return LunarDay::fromYmd($m->getYear()->getYear(), $m->getMonthWithLeap(), $days + 1);
    }

    /**
     * 法定假日，如果当天不是法定假日，返回null
     *
     * @return ?LegalHoliday 法定假日
     */
    function getLegalHoliday(): ?LegalHoliday
    {
        $m = $this->getMonth();
        return LegalHoliday::fromYmd($m->getYear()->getYear(), $m->getMonth(), $this->day);
    }

    /**
     * 公历现代节日，如果当天不是公历现代节日，返回null
     *
     * @return ?SolarFestival 公历现代节日
     */
    function getFestival(): ?SolarFestival
    {
        $m = $this->getMonth();
        return SolarFestival::fromYmd($m->getYear()->getYear(), $m->getMonth(), $this->day);
    }

}
