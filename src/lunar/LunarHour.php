<?php

namespace com\tyme\lunar;


use com\tyme\AbstractTyme;
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
use InvalidArgumentException;

/**
 * 农历时辰
 * @author 6tail
 * @package com\tyme\lunar
 */
class LunarHour extends AbstractTyme
{
    /**
     * @var EightCharProvider|null 八字计算接口
     */
    static ?EightCharProvider $provider = null;

    /**
     * @var LunarDay 农历日
     */
    protected LunarDay $day;

    /**
     * @var int 时
     */
    protected int $hour;

    /**
     * @var int 分
     */
    protected int $minute;

    /**
     * @var int 秒
     */
    protected int $second;

    /**
     * @var SolarTime|null 公历时刻（第一次使用时才会初始化）
     */
    protected ?SolarTime $solarTime = null;

    /**
     * @var SixtyCycleHour|null 干支时辰（第一次使用时才会初始化）
     */
    protected ?SixtyCycleHour $sixtyCycleHour = null;

    private static function init(): void
    {
        static::$provider = new DefaultEightCharProvider();
    }

    protected function __construct(int $year, int $month, int $day, int $hour, int $minute, int $second)
    {
        if (null == static::$provider) {
            static::init();
        }
        if ($hour < 0 || $hour > 23) {
            throw new InvalidArgumentException(sprintf('illegal hour: %d', $hour));
        }
        if ($minute < 0 || $minute > 59) {
            throw new InvalidArgumentException(sprintf('illegal minute: %d', $minute));
        }
        if ($second < 0 || $second > 59) {
            throw new InvalidArgumentException(sprintf('illegal second: %d', $second));
        }
        $this->day = LunarDay::fromYmd($year, $month, $day);
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
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
        return $this->day;
    }

    /**
     * 年
     *
     * @return int 年
     */
    function getYear(): int
    {
        return $this->day->getYear();
    }

    /**
     * 月
     *
     * @return int 月，闰月为负数
     */
    function getMonth(): int
    {
        return $this->day->getMonth();
    }

    /**
     * 日
     *
     * @return int 日
     */
    function getDay(): int
    {
        return $this->day->getDay();
    }

    /**
     * 时
     *
     * @return int 时
     */
    function getHour(): int
    {
        return $this->hour;
    }

    /**
     * 分
     *
     * @return int 分
     */
    function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * 秒
     *
     * @return int 秒
     */
    function getSecond(): int
    {
        return $this->second;
    }

    function getName(): string
    {
        return sprintf('%s时', EarthBranch::fromIndex($this->getIndexInDay())->getName());
    }

    function __toString(): string
    {
        return sprintf('%s%s时', $this->day, $this->getSixtyCycle()->getName());
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
        if (!$this->day->equals($target->getLunarDay())) {
            return $this->day->isBefore($target->getLunarDay());
        }
        if ($this->hour != $target->getHour()) {
            return $this->hour < $target->getHour();
        }
        return $this->minute != $target->getMinute() ? $this->minute < $target->getMinute() : $this->second < $target->getSecond();
    }

    /**
     * 是否在指定农历时辰之后
     *
     * @param LunarHour $target 农历时辰
     * @return true/false
     */
    function isAfter(LunarHour $target): bool
    {
        if (!$this->day->equals($target->getLunarDay())) {
            return $this->day->isAfter($target->getLunarDay());
        }
        if ($this->hour != $target->getHour()) {
            return $this->hour > $target->getHour();
        }
        return $this->minute != $target->getMinute() ? $this->minute > $target->getMinute() : $this->second > $target->getSecond();
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
        $d = $this->day->next($days);
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
        $d = $this->day->getSixtyCycle();
        if ($this->hour >= 23) {
            $d = $d->next(1);
        }
        $heavenStemIndex = $d->getHeavenStem()->getIndex() % 5 * 2 + $earthBranchIndex;
        return SixtyCycle::fromName(sprintf('%s%s', HeavenStem::fromIndex($heavenStemIndex)->getName(), EarthBranch::fromIndex($earthBranchIndex)->getName()));
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
        $solar = $this->day->getSolarDay();
        $dongZhi = SolarTerm::fromIndex($solar->getYear(), 0);
        $xiaZhi = $dongZhi->next(12);
        $asc = !$solar->isBefore($dongZhi->getJulianDay()->getSolarDay()) && $solar->isBefore($xiaZhi->getJulianDay()->getSolarDay());
        $start = [8, 5, 2][$this->day->getSixtyCycle()->getEarthBranch()->getIndex() % 3];
        if ($asc) {
            $start = 8 - $start;
        }
        $earthBranchIndex = $this->getIndexInDay() % 12;
        return NineStar::fromIndex($start + ($asc ? $earthBranchIndex : -$earthBranchIndex));
    }

    /**
     * 公历时刻
     *
     * @return SolarTime 公历时刻
     */
    function getSolarTime(): SolarTime
    {
        if ($this->solarTime == null)
        {
            $d = $this->day->getSolarDay();
            $this->solarTime = SolarTime::fromYmdHms($d->getYear(), $d->getMonth(), $d->getDay(), $this->hour, $this->minute, $this->second);
        }
        return $this->solarTime;
    }

    function getSixtyCycleHour(): SixtyCycleHour
    {
        if ($this->sixtyCycleHour == null)
        {
            $this->sixtyCycleHour = $this->getSolarTime()->getSixtyCycleHour();
        }
        return $this->sixtyCycleHour;
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
