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
        return match (true) {
            $code == 0 => self::WOMAN,
            $code == 1 => self::MAN,
            default => null
        };
    }

    static function fromName(string $name): Gender
    {
        return match (true) {
            $name == '女' => self::WOMAN,
            $name == '男' => self::MAN,
            default => null
        };
    }

    function equals(Side $o): bool
    {
        return $this->value == $o->value;
    }

}
