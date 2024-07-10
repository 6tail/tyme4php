<?php

namespace com\tyme\solar;


use com\tyme\AbstractTyme;
use com\tyme\culture\Week;
use InvalidArgumentException;

/**
 * 公历周
 * @author 6tail
 * @package com\tyme\solar
 */
class SolarWeek extends AbstractTyme
{
    static array $NAMES = ['第一周', '第二周', '第三周', '第四周', '第五周', '第六周'];

    /**
     * @var SolarMonth 月
     */
    protected SolarMonth $month;

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
            throw new InvalidArgumentException(sprintf('illegal solar week index: %d', $index));
        }
        if ($start < 0 || $start > 6) {
            throw new InvalidArgumentException(sprintf('illegal solar week start: %d', $start));
        }
        $m = SolarMonth::fromYm($year, $month);
        if ($index >= $m->getWeekCount($start)) {
            throw new InvalidArgumentException(sprintf('illegal solar week index: %d in month: %s', $index, $m));
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
     * 公历月
     *
     * @return SolarMonth 公历月
     */
    function getSolarMonth(): SolarMonth
    {
        return $this->month;
    }

    /**
     * 年
     *
     * @return int 年
     */
    function getYear(): int
    {
        return $this->month->getYear();
    }

    /**
     * 月
     *
     * @return int 月
     */
    function getMonth(): int
    {
        return $this->month->getMonth();
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
     * 位于当年的索引
     *
     * @return int 索引
     */
    function getIndexInYear(): int
    {
        $i = 0;
        // 今年第1周
        $w = self::fromYm($this->getYear(), 1, 0, $this->start->getIndex());
        while (!$w->equals($this)) {
            $w = $w->next(1);
            $i += 1;
        }
        return $i;
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
            return static::fromYm($this->getYear(), $this->getMonth(), $this->index, $this->start->getIndex());
        }
        $d = $this->index + $n;
        $m = $this->month;
        $startIndex = $this->start->getIndex();
        $weekCount = $m->getWeekCount($startIndex);
        $forward = $n > 0;
        $add = $forward ? 1 : -1;
        while ($forward ? ($d >= $weekCount) : ($d < 0)) {
            if ($forward) {
                $d -= $weekCount;
            } else {
                if (!SolarDay::fromYmd($m->getYear(), $m->getMonth(), 1)->getWeek()->equals($this->start)) {
                    $d += $add;
                }
            }
            $m = $m->next($add);
            if ($forward) {
                if (!SolarDay::fromYmd($m->getYear(), $m->getMonth(), 1)->getWeek()->equals($this->start)) {
                    $d += $add;
                }
            }
            $weekCount = $m->getWeekCount($startIndex);
            if (!$forward) {
                $d += $weekCount;
            }
        }
        return static::fromYm($m->getYear(), $m->getMonth(), $d, $startIndex);
    }

    /**
     * 本周第1天
     *
     * @return SolarDay 公历日
     */
    function getFirstDay(): SolarDay
    {
        $firstDay = SolarDay::fromYmd($this->getYear(), $this->getMonth(), 1);
        return $firstDay->next($this->index * 7 - $this->indexOf($firstDay->getWeek()->getIndex() - $this->start->getIndex(), null, 7));
    }

    /**
     * 本周公历日列表
     *
     * @return SolarDay[] 公历日列表
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
