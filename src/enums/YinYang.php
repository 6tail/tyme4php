<?php

namespace com\tyme\enums;

/**
 * 阴阳
 * @author 6tail
 * @package com\tyme\enums
 */
enum YinYang: int
{
    case YIN = 0;
    case YANG = 1;

    function getCode(): int
    {
        return $this->value;
    }

    function getName(): string
    {
        return match ($this) {
            self::YIN => '阴',
            self::YANG => '阳'
        };
    }

    static function fromCode(int $code): YinYang
    {
        return match ($code) {
            1 => self::YANG,
            0 => self::YIN,
            default => null
        };
    }

    static function fromName(string $name): YinYang
    {
        return match ($name) {
            '阳' => self::YANG,
            '阴' => self::YIN,
            default => null
        };
    }

    function equals(YinYang $o): bool
    {
        return $this->value === $o->value;
    }

}
