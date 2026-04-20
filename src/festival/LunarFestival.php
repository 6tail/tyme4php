<?php

namespace com\tyme\festival;


use com\tyme\enums\EventType;
use com\tyme\enums\FestivalType;
use com\tyme\event\Event;
use com\tyme\lunar\LunarDay;
use com\tyme\solar\SolarTerm;
use com\tyme\unit\DayUnit;

/**
 * 农历传统节日（依据国家标准《农历的编算和颁行》GB/T 33661-2017）
 * @author 6tail
 * @package com\tyme\festival
 */
class LunarFestival extends AbstractFestival
{
    static array $NAMES = ['春节', '元宵节', '龙头节', '上巳节', '清明节', '端午节', '七夕节', '中元节', '中秋节', '重阳节', '冬至节', '腊八节', '除夕'];

    static string $DATA = '2VV__0002Vj__0002WW__0002XX__0003b___0002ZZ__0002bb__0002bj__0002cj__0002dd__0003s___0002gc__0002hV_U000';

    protected function __construct(FestivalType $type, int $index, Event $event, DayUnit $day)
    {
        parent::__construct($type, $index, $event, $day);
    }

    static function fromIndex(int $year, int $index): ?static
    {
        if ($index < 0 || $index >= count(static::$NAMES)) {
            return null;
        }
        $start = $index * 8;
        $e = new Event(static::$NAMES[$index], '@' . substr(static::$DATA, $start, 8));
        switch ($e->getType()) {
            case EventType::LUNAR_DAY:
                $m = $e->getMonth($year);
                $d = LunarDay::fromYmd($m[0], $m[1], $e->getValue(3));
                $offset = $e->getValue(5);
                return new static(FestivalType::DAY, $index, $e, $offset === 0 ? $d : $d->next($offset));
            case EventType::TERM_DAY:
                return new static(FestivalType::TERM, $index, $e, SolarTerm::fromIndex($year, $e->getValue(2))->getSolarDay()->getLunarDay());
            default:
                return null;
        }
    }

    static function fromYmd(int $year, int $month, int $day): ?static
    {
        $d = LunarDay::fromYmd($year, $month, $day);
        for ($i = 0, $j = count(static::$NAMES); $i < $j; $i++) {
            $start = $i * 8;
            $e = new Event(static::$NAMES[$i], '@' . substr(static::$DATA, $start, 8));
            switch ($e->getType()) {
                case EventType::LUNAR_DAY:
                    $offset = $e->getValue(5);
                    if ($offset === 0) {
                        if ($d->getMonth() === $e->getValue(2) && $d->getDay() === $e->getValue(3)) {
                            return new static(FestivalType::DAY, $i, $e, $d);
                        }
                    } else {
                        $m = $e->getMonth($year);
                        $next = $d->next(-$offset);
                        if ($next->getYear() === $m[0] && $next->getMonth() === $m[1] && $next->getDay() === $e->getValue(3)) {
                            return new static(FestivalType::DAY, $i, $e, $d);
                        }
                    }
                    break;
                case EventType::TERM_DAY:
                    $term = $d->getSolarDay()->getTermDay();
                    if ($term->getDayIndex() === 0 && $term->getSolarTerm()->getIndex() === $e->getValue(2) % 24) {
                        return new static(FestivalType::TERM, $i, $e, $d);
                    }
                default:
            }
        }
        return null;
    }

    function next(int $n): static
    {
        $size = count(static::$NAMES);
        $i = $this->index + $n;
        return static::fromIndex(intdiv($this->day->getYear() * $size + $i, $size), $this->indexOf($i, null, $size));
    }

    /**
     * @return LunarDay 农历日
     */
    function getDay(): LunarDay
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

    /**
     * 节气，非节气返回null
     *
     * @return ?SolarTerm 节气
     */
    function getSolarTerm(): ?SolarTerm
    {
        $t = $this->getDay()->getSolarDay()->getTermDay();
        return $t->getDayIndex() === 0 ? $t->getSolarTerm() : null;
    }
}
