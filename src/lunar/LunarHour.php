<?php

namespace com\tyme\lunar;


use com\tyme\culture\ren\MinorRen;
use com\tyme\culture\star\nine\NineStar;
use com\tyme\culture\star\twelve\TwelveStar;
use com\tyme\culture\Taboo;
use com\tyme\eightchar\EightChar;
use com\tyme\eightchar\provider\EightCharProvider;
use com\tyme\eightchar\provider\impl\DefaultEightCharProvider;
use com\tyme\sixtycycle\EarthBranch;
use com\tyme\sixtycycle\HeavenStem;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\sixtycycle\SixtyCycleHour;
use com\tyme\solar\SolarTerm;
use com\tyme\solar\SolarTime;
use com\tyme\unit\SecondUnit;

/**
 * 农历时辰
 * @author 6tail
 * @package com\tyme\lunar
 */
class LunarHour extends SecondUnit
{
    /**
     * @var EightCharProvider|null 八字计算接口
     */
    static ?EightCharProvider $provider = null;

    private static function init(): void
    {
        static::$provider = new DefaultEightCharProvider();
    }

    protected function __construct(int $year, int $month, int $day, int $hour, int $minute, int $second)
    {
        if (null === static::$provider) {
            static::init();
        }
        self::validate($year, $month, $day, $hour, $minute, $second);
        parent::__construct($year, $month, $day, $hour, $minute, $second);
    }

    static function validate($year, $month, $day, $hour, $minute, $second): void
    {
        parent::validate($year, $month, $day, $hour, $minute, $second);
        LunarDay::validate($year, $month, $day);
    }

    static function fromYmdHms(int $year, int $month, int $day, int $hour, int $minute, int $second): static
    {
        return new static($year, $month, $day, $hour, $minute, $second);
    }

    /**
     * 农历日
     *
     * @return LunarDay 农历日
     */
    function getLunarDay(): LunarDay
    {
        return LunarDay::fromYmd($this->year, $this->month, $this->day);
    }

    function getName(): string
    {
        return sprintf('%s时', EarthBranch::fromIndex($this->getIndexInDay())->getName());
    }

    function __toString(): string
    {
        return sprintf('%s%s时', $this->getLunarDay(), $this->getSixtyCycle()->getName());
    }

    function getIndexInDay(): int
    {
        return intdiv($this->hour + 1, 2);
    }

    /**
     * 是否在指定农历时辰之前
     *
     * @param LunarHour $target 农历时辰
     * @return bool true/false
     */
    function isBefore(LunarHour $target): bool
    {
        $aDay = $this->getLunarDay();
        $bDay = $target->getLunarDay();
        if (!$aDay->equals($bDay)) {
            return $aDay->isBefore($bDay);
        }
        if ($this->hour != $target->hour) {
            return $this->hour < $target->hour;
        }
        return $this->minute != $target->minute ? $this->minute < $target->minute : $this->second < $target->second;
    }

    /**
     * 是否在指定农历时辰之后
     *
     * @param LunarHour $target 农历时辰
     * @return true/false
     */
    function isAfter(LunarHour $target): bool
    {
        $aDay = $this->getLunarDay();
        $bDay = $target->getLunarDay();
        if (!$aDay->equals($bDay)) {
            return $aDay->isAfter($bDay);
        }
        if ($this->hour != $target->hour) {
            return $this->hour > $target->hour;
        }
        return $this->minute != $target->minute ? $this->minute > $target->minute : $this->second > $target->second;
    }

    function next(int $n): LunarHour
    {
        $h = $this->hour + $n * 2;
        $diff = $h < 0 ? -1 : 1;
        $hour = abs($h);
        $days = intdiv($hour, 24) * $diff;
        $hour = ($hour % 24) * $diff;
        if ($hour < 0) {
            $hour += 24;
            $days--;
        }
        $d = $this->getLunarDay()->next($days);
        return static::fromYmdHms($d->getYear(), $d->getMonth(), $d->getDay(), $hour, $this->minute, $this->second);
    }

    /**
     * 当时的年干支（立春换）
     *
     * @return SixtyCycle 干支
     * @deprecated
     * @see SixtyCycleHour
     */
    function getYearSixtyCycle(): SixtyCycle
    {
        return $this->getSixtyCycleHour()->getYear();
    }

    /**
     * 当时的月干支（节气换）
     *
     * @return SixtyCycle 干支
     * @deprecated
     * @see SixtyCycleHour
     */
    function getMonthSixtyCycle(): SixtyCycle
    {
        return $this->getSixtyCycleHour()->getMonth();
    }

    /**
     * 当时的日干支（23:00开始算做第二天）
     *
     * @return SixtyCycle 干支
     * @deprecated
     * @see SixtyCycleHour
     */
    function getDaySixtyCycle(): SixtyCycle
    {
        return $this->getSixtyCycleHour()->getDay();
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        $earthBranchIndex = $this->getIndexInDay() % 12;
        $d = $this->getLunarDay()->getSixtyCycle();
        if ($this->hour >= 23) {
            $d = $d->next(1);
        }
        return SixtyCycle::fromName(sprintf('%s%s', HeavenStem::fromIndex($d->getHeavenStem()->getIndex() % 5 * 2 + $earthBranchIndex)->getName(), EarthBranch::fromIndex($earthBranchIndex)->getName()));
    }

    /**
     * 黄道黑道十二神
     *
     * @return TwelveStar 黄道黑道十二神
     */
    function getTwelveStar(): TwelveStar
    {
        return TwelveStar::fromIndex($this->getSixtyCycle()->getEarthBranch()->getIndex() + (8 - $this->getSixtyCycleHour()->getDay()->getEarthBranch()->getIndex() % 6) * 2);
    }

    /**
     * 九星（时家紫白星歌诀：三元时白最为佳，冬至阳生顺莫差，孟日七宫仲一白，季日四绿发萌芽，每把时辰起甲子，本时星耀照光华，时星移入中宫去，顺飞八方逐细查。夏至阴生逆回首，孟归三碧季加六，仲在九宫时起甲，依然掌中逆轮跨。）
     *
     * @return NineStar 九星
     */
    function getNineStar(): NineStar
    {
        $d = $this->getLunarDay();
        $solar = $d->getSolarDay();
        $dongZhi = SolarTerm::fromIndex($solar->getYear(), 0);
        $earthBranchIndex = $this->getIndexInDay() % 12;
        $index = [8, 5, 2][$d->getSixtyCycle()->getEarthBranch()->getIndex() % 3];
        if (!$solar->isBefore($dongZhi->getJulianDay()->getSolarDay()) && $solar->isBefore($dongZhi->next(12)->getJulianDay()->getSolarDay())) {
            $index = 8 + $earthBranchIndex - $index;
        } else {
            $index -= $earthBranchIndex;
        }
        return NineStar::fromIndex($index);
    }

    /**
     * 公历时刻
     *
     * @return SolarTime 公历时刻
     */
    function getSolarTime(): SolarTime
    {
        $d = $this->getLunarDay()->getSolarDay();
        return SolarTime::fromYmdHms($d->getYear(), $d->getMonth(), $d->getDay(), $this->hour, $this->minute, $this->second);
    }

    function getSixtyCycleHour(): SixtyCycleHour
    {
        return $this->getSolarTime()->getSixtyCycleHour();
    }

    /**
     * 八字
     *
     * @return EightChar 八字
     */
    function getEightChar(): EightChar
    {
        return static::$provider->getEightChar($this);
    }

    /**
     * 宜
     * @return Taboo[] 宜忌列表
     */
    function getRecommends(): array
    {
        return Taboo::getHourRecommends($this->getSixtyCycleHour()->getDay(), $this->getSixtyCycle());
    }

    /**
     * 忌
     * @return Taboo[] 宜忌列表
     */
    function getAvoids(): array
    {
        return Taboo::getHourAvoids($this->getSixtyCycleHour()->getDay(), $this->getSixtyCycle());
    }

    /**
     * 小六壬
     * @return MinorRen 小六壬
     */
    function getMinorRen(): MinorRen
    {
        return $this->getLunarDay()->getMinorRen()->next($this->getIndexInDay());
    }
}
