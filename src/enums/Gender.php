<?php

namespace com\tyme\enums;

/**
 * 性别
 * @author 6tail
 * @package com\tyme\enums
 */
enum Gender: int
{
    case WOMAN = 0;
    case MAN = 1;

    function getCode(): int
    {
        return $this->value;
    }

    function getName(): string
    {
        return match ($this) {
            self::WOMAN => '女',
            self::MAN => '男'
        };
    }

    static function fromCode(int $code): Gender
    {
        return match ($code) {
            0 => self::WOMAN,
            1 => self::MAN,
            default => null
        };
    }

    static function fromName(string $name): Gender
    {
        return match ($name) {
            '女' => self::WOMAN,
            '男' => self::MAN,
            default => null
        };
    }

    function equals(Gender $o): bool
    {
        return $this->value === $o->value;
    }

}
