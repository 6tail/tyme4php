<?php

namespace com\tyme\festival;


use com\tyme\AbstractTyme;
use com\tyme\enums\FestivalType;
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
     * 类型
     * @deprecated
     */
    protected FestivalType $type;

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

    protected function __construct(FestivalType $type, int $index, Event $event, DayUnit $day)
    {
        $this->type = $type;
        $this->index = $index;
        $this->event = $event;
        $this->day = $day;
    }

    function __toString(): string
    {
        return sprintf('%s %s', $this->day, $this->getName());
    }

    /**
     * 类型
     * @deprecated
     * @return FestivalType 节日类型
     */
    function getType(): FestivalType
    {
        return $this->type;
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
