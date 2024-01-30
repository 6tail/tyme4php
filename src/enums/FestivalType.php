<?php

namespace com\tyme\enums;

/**
 * 节日类型
 * @author 6tail
 * @package com\tyme\enums
 */
enum FestivalType: int
{
    case DAY = 0;
    case TERM = 1;
    case EVE = 2;

    function getCode(): int
    {
        return $this->value;
    }

    function getName(): string
    {
        return match ($this) {
            self::DAY => '日期',
            self::TERM => '节气',
            self::EVE => '除夕'
        };
    }

    static function fromCode(int $code): FestivalType
    {
        return match (true) {
            $code == 0 => self::DAY,
            $code == 1 => self::TERM,
            $code == 2 => self::EVE,
            default => null
        };
    }

    static function fromName(string $name): FestivalType
    {
        return match (true) {
            $name == '日期' => self::DAY,
            $name == '节气' => self::TERM,
            $name == '除夕' => self::EVE,
            default => null
        };
    }

    function equals(Side $o): bool
    {
        return $this->value == $o->value;
    }

}
