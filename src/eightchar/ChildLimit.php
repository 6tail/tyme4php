<?php

namespace com\tyme\eightchar;


use com\tyme\enums\Gender;
use com\tyme\enums\YinYang;
use com\tyme\solar\SolarMonth;
use com\tyme\solar\SolarTime;

/**
 * 童限（从出生到起运的时间段）
 * @author 6tail
 * @package com\tyme\eightchar
 */
class ChildLimit
{
    /**
     * @var SolarTime 开始(即出生)的公历时刻
     */
    protected SolarTime $startTime;

    /**
     * @var SolarTime 结束(即开始起运)的公历时刻
     */
    protected SolarTime $endTime;

    /**
     * @var EightChar 八字
     */
    protected EightChar $eightChar;

    /**
     * 性别
     */
    protected Gender $gender;

    /**
     * @var int 年数
     */
    protected int $yearCount;

    /**
     * @var int 月数
     */
    protected int $monthCount;

    /**
     * @var int 日数
     */
    protected int $dayCount;

    /**
     * @var int 小时数
     */
    protected int $hourCount;

    /**
     * @var int 分钟数
     */
    protected int $minuteCount;

    /**
     * @var bool 顺逆
     */
    protected bool $forward;

    protected function __construct(SolarTime $birthTime, Gender $gender)
    {
        $this->startTime = $birthTime;
        $this->gender = $gender;
        $this->eightChar = $birthTime->getLunarHour()->getEightChar();
        // 阳男阴女顺推，阴男阳女逆推
        $yang = YinYang::YANG == $this->eightChar->getYear()->getHeavenStem()->getYinYang();
        $man = Gender::MAN == $gender;
        $forward = ($yang && $man) || (!$yang && !$man);
        $term = $birthTime->getTerm();
        if (!$term->isJie()) {
            $term = $term->next(-1);
        }
        $start = $forward ? $birthTime : $term->getJulianDay()->getSolarTime();
        $end = $forward ? $term->next(2)->getJulianDay()->getSolarTime() : $birthTime;

        $seconds = $end->subtract($start);
        // 3天 = 1年，3天=60*60*24*3秒=259200秒 = 1年
        $year = intdiv($seconds, 259200);
        $seconds %= 259200;
        // 1天 = 4月，1天=60*60*24秒=86400秒 = 4月，85400秒/4=21600秒 = 1月
        $month = intdiv($seconds, 21600);
        $seconds %= 21600;
        // 1时 = 5天，1时=60*60秒=3600秒 = 5天，3600秒/5=720秒 = 1天
        $day = intdiv($seconds, 720);
        $seconds %= 720;
        // 1分 = 2时，60秒 = 2时，60秒/2=30秒 = 1时
        $hour = intdiv($seconds, 30);
        $seconds %= 30;
        // 1秒 = 2分，1秒/2=0.5秒 = 1分
        $minute = $seconds * 2;

        $this->forward = $forward;
        $this->yearCount = $year;
        $this->monthCount = $month;
        $this->dayCount = $day;
        $this->hourCount = $hour;
        $this->minuteCount = $minute;

        $birthday = $birthTime->getDay();
        $birthMonth = $birthday->getMonth();

        $d = $birthday->getDay() + $day;
        $h = $birthTime->getHour() + $hour;
        $mi = $birthTime->getMinute() + $minute;
        $h += intdiv($mi, 60);
        $mi %= 60;
        $d += intdiv($h, 24);
        $h %= 24;

        $sm = SolarMonth::fromYm($birthMonth->getYear()->getYear() + $year, $birthMonth->getMonth())->next($month);

        $dc = $sm->getDayCount();
        if ($d > $dc) {
            $d -= $dc;
            $sm = $sm->next(1);
        }
        $this->endTime = SolarTime::fromYmdHms($sm->getYear()->getYear(), $sm->getMonth(), $d, $h, $mi, $birthTime->getSecond());
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
        return $this->yearCount;
    }

    /**
     * 月数
     *
     * @return int 月数
     */
    function getMonthCount(): int
    {
        return $this->monthCount;
    }

    /**
     * 日数
     *
     * @return int 日数
     */
    function getDayCount(): int
    {
        return $this->dayCount;
    }

    /**
     * 小时数
     *
     * @return int 小时数
     */
    function getHourCount(): int
    {
        return $this->hourCount;
    }

    /**
     * 分钟数
     *
     * @return int 分钟数
     */
    function getMinuteCount(): int
    {
        return $this->minuteCount;
    }

    /**
     * 开始(即出生)的公历时刻
     *
     * @return SolarTime 公历时刻
     */
    function getStartTime(): SolarTime
    {
        return $this->startTime;
    }

    /**
     * 结束(即开始起运)的公历时刻
     *
     * @return SolarTime 公历时刻
     */
    function getEndTime(): SolarTime
    {
        return $this->endTime;
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

}
