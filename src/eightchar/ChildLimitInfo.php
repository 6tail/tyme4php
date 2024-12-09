<?php

namespace com\tyme\eightchar;


use com\tyme\solar\SolarTime;

/**
 * 童限信息
 * @author 6tail
 * @package com\tyme\eightchar
 */
class ChildLimitInfo
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
     * 初始化
     * @param SolarTime $startTime 开始(即出生)的公历时刻
     * @param SolarTime $endTime 结束(即开始起运)的公历时刻
     * @param int $yearCount 年数
     * @param int $monthCount 月数
     * @param int $dayCount 日数
     * @param int $hourCount 小时数
     * @param int $minuteCount 分钟数
     */
    function __construct(SolarTime $startTime, SolarTime $endTime, int $yearCount, int $monthCount, int $dayCount, int $hourCount, int $minuteCount)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->yearCount = $yearCount;
        $this->monthCount = $monthCount;
        $this->dayCount = $dayCount;
        $this->hourCount = $hourCount;
        $this->minuteCount = $minuteCount;
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

}
