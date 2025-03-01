<?php

namespace com\tyme\eightchar;


use com\tyme\eightchar\provider\ChildLimitProvider;
use com\tyme\eightchar\provider\impl\DefaultChildLimitProvider;
use com\tyme\enums\Gender;
use com\tyme\enums\YinYang;
use com\tyme\lunar\LunarYear;
use com\tyme\solar\SolarTime;

/**
 * 童限（从出生到起运的时间段）
 * @author 6tail
 * @package com\tyme\eightchar
 */
class ChildLimit
{
    /**
     * @var ChildLimitProvider|null 童限计算接口
     */
    static ?ChildLimitProvider $provider = null;

    /**
     * @var EightChar 八字
     */
    protected EightChar $eightChar;

    /**
     * 性别
     */
    protected Gender $gender;

    /**
     * @var bool 顺逆
     */
    protected bool $forward;

    /**
     * @var ChildLimitInfo 童限信息
     */
    protected ChildLimitInfo $info;

    private static function init(): void
    {
        self::$provider = new DefaultChildLimitProvider();
    }

    protected function __construct(SolarTime $birthTime, Gender $gender)
    {
        if (null == self::$provider) {
            self::init();
        }
        $this->gender = $gender;
        $this->eightChar = $birthTime->getLunarHour()->getEightChar();
        // 阳男阴女顺推，阴男阳女逆推
        $yang = YinYang::YANG == $this->eightChar->getYear()->getHeavenStem()->getYinYang();
        $man = Gender::MAN == $gender;
        $this->forward = ($yang && $man) || (!$yang && !$man);
        $term = $birthTime->getTerm();
        if (!$term->isJie()) {
            $term = $term->next(-1);
        }
        if ($this->forward) {
            $term = $term->next(2);
        }
        $this->info = self::$provider->getInfo($birthTime, $term);
    }

    /**
     * 通过出生公历时刻初始化
     *
     * @param SolarTime $birthTime 出生公历时刻
     * @param Gender $gender 性别
     * @return static 童限
     */
    static function fromSolarTime(SolarTime $birthTime, Gender $gender): static
    {
        return new static($birthTime, $gender);
    }

    /**
     * 八字
     *
     * @return EightChar 八字
     */
    function getEightChar(): EightChar
    {
        return $this->eightChar;
    }

    /**
     * 性别
     *
     * @return Gender 性别
     */
    function getGender(): Gender
    {
        return $this->gender;
    }

    /**
     * 是否顺推
     *
     * @return bool true/false
     */
    function isForward(): bool
    {
        return $this->forward;
    }

    /**
     * 年数
     *
     * @return int 年数
     */
    function getYearCount(): int
    {
        return $this->info->getYearCount();
    }

    /**
     * 月数
     *
     * @return int 月数
     */
    function getMonthCount(): int
    {
        return $this->info->getMonthCount();
    }

    /**
     * 日数
     *
     * @return int 日数
     */
    function getDayCount(): int
    {
        return $this->info->getDayCount();
    }

    /**
     * 小时数
     *
     * @return int 小时数
     */
    function getHourCount(): int
    {
        return $this->info->getHourCount();
    }

    /**
     * 分钟数
     *
     * @return int 分钟数
     */
    function getMinuteCount(): int
    {
        return $this->info->getMinuteCount();
    }

    /**
     * 开始(即出生)的公历时刻
     *
     * @return SolarTime 公历时刻
     */
    function getStartTime(): SolarTime
    {
        return $this->info->getStartTime();
    }

    /**
     * 结束(即开始起运)的公历时刻
     *
     * @return SolarTime 公历时刻
     */
    function getEndTime(): SolarTime
    {
        return $this->info->getEndTime();
    }

    /**
     * 大运
     *
     * @return DecadeFortune 大运
     */
    function getStartDecadeFortune(): DecadeFortune
    {
        return DecadeFortune::fromChildLimit($this, 0);
    }

    /**
     * 小运
     *
     * @return Fortune 小运
     */
    function getStartFortune(): Fortune
    {
        return Fortune::fromChildLimit($this, 0);
    }

    /**
     * 结束农历年
     *
     * @return LunarYear 农历年
     */
    function getEndLunarYear(): LunarYear
    {
        return LunarYear::fromYear($this->getStartTime()->getLunarHour()->getYear() + $this->getEndTime()->getYear() - $this->getStartTime()->getYear());
    }

}
