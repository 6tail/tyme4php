<?php

namespace com\tyme\lunar;


use com\tyme\AbstractTyme;
use com\tyme\culture\Week;
use InvalidArgumentException;

/**
 * 农历周
 * @author 6tail
 * @package com\tyme\lunar
 */
class LunarWeek extends AbstractTyme
{
    static array $NAMES = ['第一周', '第二周', '第三周', '第四周', '第五周', '第六周'];

    /**
     * @var LunarMonth 月
     */
    protected LunarMonth $month;

    /**
     * @var int 索引，0-5
     */
    protected int $index;

    /**
     * @var Week 起始星期
     */
    protected Week $start;

    protected function __construct(int $year, int $month, int $index, int $start)
    {
        if ($index < 0 || $index > 5) {
            throw new InvalidArgumentException(sprintf('illegal lunar week index: %d', $index));
        }
        if ($start < 0 || $start > 6) {
            throw new InvalidArgumentException(sprintf('illegal lunar week start: %d', $start));
        }
        $m = LunarMonth::fromYm($year, $month);
        if ($index >= $m->getWeekCount($start)) {
            throw new InvalidArgumentException(sprintf('illegal lunar week index: %d in month: %s', $index, $m));
        }
        $this->month = $m;
        $this->index = $index;
        $this->start = Week::fromIndex($start);
    }

    static function fromYm(int $year, int $month, int $index, int $start): static
    {
        return new static($year, $month, $index, $start);
    }

    /**
     * 月
     *
     * @return LunarMonth 月
     */
    function getMonth(): LunarMonth
    {
        return $this->month;
    }

    /**
     * 索引
     *
     * @return int 索引，0-5
     */
    function getIndex(): int
    {
        return $this->index;
    }

    /**
     * 起始星期
     *
     * @return Week 星期
     */
    function getStart(): Week
    {
        return $this->start;
    }

    function getName(): string
    {
        return self::$NAMES[$this->index];
    }

    function __toString(): string
    {
        return sprintf('%s%s', $this->month, $this->getName());
    }

    function next(int $n): static
    {
        if ($n == 0) {
            return static::fromYm($this->month->getYear()->getYear(), $this->month->getMonthWithLeap(), $this->index, $this->start->getIndex());
        }
        $d = $this->index + $n;
        $m = $this->month;
        $startIndex = $this->start->getIndex();
        $weeksInMonth = $m->getWeekCount($startIndex);
        $forward = $n > 0;
        $add = $forward ? 1 : -1;
        while ($forward ? ($d >= $weeksInMonth) : ($d < 0)) {
            if ($forward) {
                $d -= $weeksInMonth;
            }
            if (!$forward) {
                if (!LunarDay::fromYmd($m->getYear()->getYear(), $m->getMonthWithLeap(), 1)->getWeek()->equals($this->start)) {
                    $d += $add;
                }
            }
            $m = $m->next($add);
            if ($forward) {
                if (!LunarDay::fromYmd($m->getYear()->getYear(), $m->getMonthWithLeap(), 1)->getWeek()->equals($this->start)) {
                    $d += $add;
                }
            }
            $weeksInMonth = $m->getWeekCount($startIndex);
            if (!$forward) {
                $d += $weeksInMonth;
            }
        }
        return static::fromYm($m->getYear()->getYear(), $m->getMonthWithLeap(), $d, $startIndex);
    }

    /**
     * 本周第1天
     *
     * @return LunarDay 公历日
     */
    function getFirstDay(): LunarDay
    {
        $m = $this->getMonth();
        $firstDay = LunarDay::fromYmd($m->getYear()->getYear(), $m->getMonthWithLeap(), 1);
        return $firstDay->next($this->index * 7 - $this->indexOf($firstDay->getWeek()->getIndex() - $this->start->getIndex(), null, 7));
    }

    /**
     * 本周农历日列表
     *
     * @return LunarDay[] 农历日列表
     */
    function getDays(): array
    {
        $l = array();
        $d = $this->getFirstDay();
        $l[] = $d;
        for ($i = 1; $i < 7; $i++) {
            $l[] = $d->next($i);
        }
        return $l;
    }

}
