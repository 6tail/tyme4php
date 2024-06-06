<?php

namespace com\tyme\enums;

/**
 * 内外
 * @author 6tail
 * @package com\tyme\enums
 */
enum Side: int
{
    case IN = 0;
    case OUT = 1;

    function getCode(): int
    {
        return $this->value;
    }

    function getName(): string
    {
        return match ($this) {
            self::IN => '内',
            self::OUT => '外'
        };
    }

    static function fromCode(int $code): Side
    {
        return match ($code) {
            0 => self::IN,
            1 => self::OUT,
            default => null
        };
    }

    static function fromName(string $name): Side
    {
        return match ($name) {
            '内' => self::IN,
            '外' => self::OUT,
            default => null
        };
    }

    function equals(Side $o): bool
    {
        return $this->value == $o->value;
    }

}
