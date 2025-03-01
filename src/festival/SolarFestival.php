<?php

namespace com\tyme\festival;


use com\tyme\AbstractTyme;
use com\tyme\enums\FestivalType;
use com\tyme\solar\SolarDay;
use InvalidArgumentException;

/**
 * 公历现代节日
 * @author 6tail
 * @package com\tyme\festival
 */
class SolarFestival extends AbstractTyme
{
    static array $NAMES = ['元旦', '三八妇女节', '植树节', '五一劳动节', '五四青年节', '六一儿童节', '建党节', '八一建军节', '教师节', '国庆节'];

    static string $DATA = '@00001011950@01003081950@02003121979@03005011950@04005041950@05006011950@06007011941@07008011933@08009101985@09010011950';

    /**
     * 类型
     */
    protected FestivalType $type;

    /**
     * @var int 索引
     */
    protected int $index;

    /**
     * @var SolarDay 公历日
     */
    protected SolarDay $day;

    /**
     * @var string 名称
     */
    protected string $name;

    /**
     * @var int 起始年
     */
    protected int $startYear;

    protected function __construct(FestivalType $type, SolarDay $day, int $startYear, string $data)
    {
        $this->type = $type;
        $this->day = $day;
        $this->startYear = $startYear;
        $this->index = intval(substr($data, 1, 2));
        $this->name = static::$NAMES[$this->index];
    }

    static function fromIndex(int $year, int $index): ?static
    {
        if ($index < 0 || $index >= count(static::$NAMES)) {
            throw new InvalidArgumentException(sprintf('illegal index: %d', $index));
        }
        if(preg_match_all(sprintf('/@%02d\\d+/', $index), static::$DATA, $matches)) {
            $data = $matches[0][0];
            $type = FestivalType::fromCode(ord(substr($data, 3, 1)) - 48);
            if ($type == FestivalType::DAY) {
                $startYear = intval(substr($data, 8, 4));
                if ($year >= $startYear) {
                    return new static($type, SolarDay::fromYmd($year, intval(substr($data, 4, 2)), intval(substr($data, 6, 2))), $startYear, $data);
                }
            }
        }
        return null;
    }

    static function fromYmd(int $year, int $month, int $day): ?static
    {
        if (preg_match_all(sprintf('/@\\d{2}0%02d%02d\\d+/', $month, $day), static::$DATA, $matches)) {
            $data = $matches[0][0];
            $startYear = intval(substr($data, 8, 4));
            if ($year >= $startYear) {
                return new static(FestivalType::DAY, SolarDay::fromYmd($year, $month, $day), $startYear, $data);
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
     * 公历日
     * @return SolarDay 公历日
     */
    function getDay(): SolarDay
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
     * 起始年
     *
     * @return int 年
     */
    function getStartYear(): int
    {
        return $this->startYear;
    }
}
