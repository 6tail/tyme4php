<?php

namespace com\tyme\festival;


use com\tyme\AbstractTyme;
use com\tyme\event\Event;
use com\tyme\unit\DayUnit;

/**
 * 节日抽象
 * @author 6tail
 * @package com\tyme\festival
 */
abstract class AbstractFestival extends AbstractTyme
{
    /**
     * @var int 索引
     */
    protected int $index;

    /**
     * @var DayUnit 日
     */
    protected DayUnit $day;

    /**
     * @var Event 事件
     */
    protected Event $event;

    protected function __construct(int $index, Event $event, DayUnit $day)
    {
        $this->index = $index;
        $this->event = $event;
        $this->day = $day;
    }

    function __toString(): string
    {
        return sprintf('%s %s', $this->day, $this->getName());
    }

    /**
     * 日
     * @return DayUnit 日
     */
    function getDay(): DayUnit
    {
        return $this->day;
    }

    /**
     * 索引
     *
     * @return int 索引
     */
    function getIndex(): int
    {
        return $this->index;
    }

    function getName(): string
    {
        return $this->event->getName();
    }
}
