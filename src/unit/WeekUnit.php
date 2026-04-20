<?php

namespace com\tyme\unit;


use InvalidArgumentException;

/**
 * 周
 * @author 6tail
 * @package com\tyme\unit
 */
abstract class WeekUnit extends MonthUnit
{
    static array $NAMES = ['第一周', '第二周', '第三周', '第四周', '第五周', '第六周'];

    /**
     * @var int 索引，0-5
     */
    protected int $index;

    /**
     * @var int 起始星期，1234560分别代表星期一至星期天
     */
    protected int $start;

    protected function __construct(int $year, int $month, int $index, int $start)
    {
        parent::__construct($year, $month);
        $this->index = $index;
        $this->start = $start;
    }

    static function validate(int $year, int $month, int $index, int $start): void
    {
        if ($index < 0 || $index > 5) {
            throw new InvalidArgumentException('illegal week index: ' . $index);
        }
        if ($start < 0 || $start > 6) {
            throw new InvalidArgumentException('illegal week start: ' . $start);
        }
    }

    /**
     * 索引，0-5
     * @return int 索引，0-5
     */
    function getIndex(): int
    {
        return $this->index;
    }

    /**
     * 起始星期，1234560分别代表星期一至星期天
     * @return int 起始星期，1234560分别代表星期一至星期天
     */
    function getStart(): int
    {
        return $this->start;
    }
}
