<?php

namespace com\tyme\event;


use com\tyme\enums\EventType;

/**
 * 事件构造器
 * @author 6tail
 * @package com\tyme\event
 */
class EventBuilder
{
    /**
     * 事件名称
     */
    protected string $name = '';

    /**
     * 事件数据（长度为9的字符串）
     */
    protected string $data = '@_____000';

    /**
     * 设置名称
     * @param string $name 名称
     * @return self 事件构造器
     */
    function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    protected function getChar(int $index): string
    {
        return EventManager::CHARS[$index];
    }

    function setValue(int $index, int $n): self
    {
        $this->data[$index] = $this->getChar(31 + $n);
        return $this;
    }

    protected function content(EventType $type, int $a, int $b, int $c): self
    {
        $this->data[1] = $this->getChar($type->getCode());
        return $this->setValue(2, $a)->setValue(3, $b)->setValue(4, $c);
    }

    /**
     * 公历日
     * @param int $solarMonth 公历月（1至12）
     * @param int $solarDay 公历日（1至31）
     * @param int $delayDays 顺延天数，例如生日在2月29，非闰年没有2月29，是+1天，还是-1天（最远支持-31至31天）
     * @return self 事件构造器
     */
    function solarDay(int $solarMonth, int $solarDay, int $delayDays): self
    {
        return $this->content(EventType::SOLAR_DAY, $solarMonth, $solarDay, $delayDays);
    }

    /**
     * 农历日
     *
     * @param int $lunarMonth 农历月（-12至-1，1至12，闰月为负）
     * @param int $lunarDay 农历日（1至30）
     * @param int $delayDays 顺延天数，例如生日在某月的三十，但下一年当月可能只有29天，是+1天，还是-1天（最远支持-31至31天）
     * @return self 事件构建器
     */
    function lunarDay(int $lunarMonth, int $lunarDay, int $delayDays): self
    {
        return $this->content(EventType::LUNAR_DAY, $lunarMonth, $lunarDay, $delayDays);
    }

    /**
     * 公历第几个星期几
     *
     * @param int $solarMonth 公历月（1至12）
     * @param int $weekIndex 第几个星期（1为第1个星期，-1为倒数第1个星期）
     * @param int $week 星期几（0至6，0代表星期天，1代表星期一）
     * @return self 事件构建器
     */
    function solarWeek(int $solarMonth, int $weekIndex, int $week): self
    {
        return $this->content(EventType::SOLAR_WEEK, $solarMonth, $weekIndex, $week);
    }

    /**
     * 节气
     *
     * @param int $termIndex 节气索引（0至23）
     * @param int $delayDays 顺延天数（最远支持-31至31天）
     * @return self 事件构建器
     */
    function termDay(int $termIndex, int $delayDays): self
    {
        return $this->content(EventType::TERM_DAY, $termIndex, 0, $delayDays);
    }

    /**
     * 节气天干
     *
     * @param int $termIndex 节气索引（0至23）
     * @param int $heavenStemIndex 天干索引（0至9）
     * @param int $delayDays 顺延天数（最远支持-31至31天）
     * @return self 事件构建器
     */
    function termHeavenStem(int $termIndex, int $heavenStemIndex, int $delayDays): self
    {
        return $this->content(EventType::TERM_HS, $termIndex, $heavenStemIndex, $delayDays);
    }

    /**
     * 节气地支
     *
     * @param int $termIndex 节气索引（0至23）
     * @param int $earthBranchIndex 地支索引（0至11）
     * @param int $delayDays 顺延天数（最远支持-31至31天）
     * @return self 事件构建器
     */
    function termEarthBranch(int $termIndex, int $earthBranchIndex, int $delayDays): self
    {
        return $this->content(EventType::TERM_EB, $termIndex, $earthBranchIndex, $delayDays);
    }

    /**
     * 起始年
     *
     * @param int $year 年
     * @return self 事件构造器
     */
    function startYear(int $year): self
    {
        $size = strlen(EventManager::CHARS);
        $n = $year;
        for ($i = 0; $i < 3; $i++) {
            $this->data[8 - $i] = $this->getChar($n % $size);
            $n = intdiv($n, $size);
        }
        return $this;
    }

    /**
     * 偏移天数
     *
     * @param int $days 天数（最远支持-31至31天）
     * @return self 事件构造器
     */
    function offset(int $days): self
    {
        return $this->setValue(5, $days);
    }

    /**
     * 生成事件
     *
     * @return Event 事件
     */
    function build(): Event
    {
        return new Event($this->name, $this->data);
    }
}
