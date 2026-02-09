<?php

namespace com\tyme\enums;

/**
 * 藏干类型
 * @author 6tail
 * @package com\tyme\enums
 */
enum HideHeavenStemType: int
{
    case RESIDUAL = 0;
    case MIDDLE = 1;
    case MAIN = 2;

    function getCode(): int
    {
        return $this->value;
    }

    function getName(): string
    {
        return match ($this) {
            self::RESIDUAL => '余气',
            self::MIDDLE => '中气',
            self::MAIN => '本气'
        };
    }

    static function fromCode(int $code): HideHeavenStemType
    {
        return match ($code) {
            0 => self::RESIDUAL,
            1 => self::MIDDLE,
            2 => self::MAIN,
            default => null
        };
    }

    static function fromName(string $name): HideHeavenStemType
    {
        return match ($name) {
            '余气' => self::RESIDUAL,
            '中气' => self::MIDDLE,
            '本气' => self::MAIN,
            default => null
        };
    }

    function equals(HideHeavenStemType $o): bool
    {
        return $this->value === $o->value;
    }

}
