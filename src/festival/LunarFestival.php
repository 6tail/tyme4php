<?php

namespace com\tyme\festival;


use com\tyme\AbstractTyme;
use com\tyme\enums\FestivalType;
use com\tyme\lunar\LunarDay;
use com\tyme\solar\SolarTerm;
use InvalidArgumentException;

/**
 * 农历传统节日（依据国家标准《农历的编算和颁行》GB/T 33661-2017）
 * @author 6tail
 * @package com\tyme\festival
 */
class LunarFestival extends AbstractTyme
{
    static array $NAMES = ['春节', '元宵节', '龙头节', '上巳节', '清明节', '端午节', '七夕节', '中元节', '中秋节', '重阳节', '冬至节', '腊八节', '除夕'];

    static string $DATA = '@0000101@0100115@0200202@0300303@04107@0500505@0600707@0700715@0800815@0900909@10124@1101208@122';

    /**
     * 类型
     */
    protected FestivalType $type;

    /**
     * @var int 索引
     */
    protected int $index;

    /**
     * @var LunarDay 农历日
     */
    protected LunarDay $day;

    /**
     * @var string 名称
     */
    protected string $name;

    /**
     * @var ?SolarTerm 节气
     */
    protected ?SolarTerm $solarTerm;

    protected function __construct(FestivalType $type, LunarDay $day, ?SolarTerm $solarTerm, string $data)
    {
        $this->type = $type;
        $this->day = $day;
        $this->solarTerm = $solarTerm;
        $this->index = intval(substr($data, 1, 2));
        $this->name = static::$NAMES[$this->index];
    }

    static function fromIndex(int $year, int $index): ?static
    {
        if ($index < 0 || $index >= count(static::$NAMES)) {
            throw new InvalidArgumentException(sprintf('illegal index: %d', $index));
        }
        if (preg_match_all(sprintf('/@%02d\\d+/', $index), static::$DATA, $matches)) {
            $data = $matches[0][0];
            $type = FestivalType::fromCode(ord(substr($data, 3, 1)) - 48);
            switch ($type) {
                case FestivalType::DAY:
                    return new static($type, LunarDay::fromYmd($year, intval(substr($data, 4, 2)), intval(substr($data, 6, 2))), null, $data);
                case FestivalType::TERM:
                    $solarTerm = SolarTerm::fromIndex($year, intval(substr($data, 4, 2)));
                    return new static($type, $solarTerm->getSolarDay()->getLunarDay(), $solarTerm, $data);
                case FestivalType::EVE:
                    return new static($type, LunarDay::fromYmd($year + 1, 1, 1)->next(-1), null, $data);
            }
        }
        return null;
    }

    static function fromYmd(int $year, int $month, int $day): ?static
    {
        if (preg_match_all(sprintf('/@\d{2}0%02d%02d/', $month, $day), static::$DATA, $matches)) {
            return new static(FestivalType::DAY, LunarDay::fromYmd($year, $month, $day), null, $matches[0][0]);
        }
        if (preg_match_all('/@\\d{2}1\\d{2}/', static::$DATA, $matches)) {
            $data = $matches[0][0];
            $solarTerm = SolarTerm::fromIndex($year, intval(substr($data, 4, 2)));
            $lunarDay = $solarTerm->getSolarDay()->getLunarDay();
            if ($lunarDay->getYear() == $year && $lunarDay->getMonth() == $month && $lunarDay->getDay() == $day) {
                return new static(FestivalType::TERM, $lunarDay, $solarTerm, $data);
            }
        }
        if (preg_match_all('/@\\d{2}2/', static::$DATA, $matches)) {
            $lunarDay = LunarDay::fromYmd($year, $month, $day);
            $nextDay = $lunarDay->next(1);
            if ($nextDay->getMonth() == 1 && $nextDay->getDay() == 1) {
                return new static(FestivalType::EVE, $lunarDay, null, $matches[0][0]);
            }
        }
        return null;
    }

    function next(int $n): static
    {
        $size = count(static::$NAMES);
        $i = $this->index + $n;
        return static::fromIndex(intdiv($this->day->getYear() * $size + $i, $size), $this->indexOf($i, null, $size));
    }

    function __toString(): string
    {
        return sprintf('%s %s', $this->day, $this->name);
    }

    /**
     * 类型
     * @return FestivalType 节日类型
     */
    function getType(): FestivalType
    {
        return $this->type;
    }

    /**
     * @return LunarDay 农历日
     */
    function getDay(): LunarDay
    {
        return $this->day;
    }

    /**
     * 索引
     *
     * @return int 索引
     */
    function getIndex(): int
    {
        return $this->index;
    }

    function getName(): string
    {
        return $this->name;
    }

    /**
     * 节气，非节气返回null
     *
     * @return SolarTerm 节气
     */
    function getSolarTerm(): SolarTerm
    {
        return $this->solarTerm;
    }
}
