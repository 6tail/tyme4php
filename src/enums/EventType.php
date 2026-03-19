<?php

namespace com\tyme\enums;

/**
 * 事件类型
 * @author 6tail
 * @package com\tyme\enums
 */
enum EventType: int
{
    case SOLAR_DAY = 0;
    case SOLAR_WEEK = 1;
    case LUNAR_DAY = 2;
    case TERM_DAY = 3;
    case TERM_HS = 4;
    case TERM_EB = 5;

    function getCode(): int
    {
        return $this->value;
    }

    function getName(): string
    {
        return match ($this) {
            self::SOLAR_DAY => '公历日期',
            self::SOLAR_WEEK => '几月第几个星期几',
            self::LUNAR_DAY => '农历日期',
            self::TERM_DAY => '节气日期',
            self::TERM_HS => '节气天干',
            self::TERM_EB => '节气地支',
        };
    }

    static function fromCode(int $code): EventType
    {
        return match ($code) {
            0 => self::SOLAR_DAY,
            1 => self::SOLAR_WEEK,
            2 => self::LUNAR_DAY,
            3 => self::TERM_DAY,
            4 => self::TERM_HS,
            5 => self::TERM_EB,
            default => null
        };
    }

    static function fromName(string $name): EventType
    {
        return match ($name) {
            '公历日期' => self::SOLAR_DAY,
            '几月第几个星期几' => self::SOLAR_WEEK,
            '农历日期' => self::LUNAR_DAY,
            '节气日期' => self::TERM_DAY,
            '节气天干' => self::TERM_HS,
            '节气地支' => self::TERM_EB,
            default => null
        };
    }

    function equals(EventType $o): bool
    {
        return $this->value === $o->value;
    }

}
