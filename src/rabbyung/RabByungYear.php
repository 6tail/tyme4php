<?php

namespace com\tyme\rabbyung;

use com\tyme\culture\Zodiac;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\solar\SolarYear;
use com\tyme\unit\AbstractTraditionalYear;

/**
 * 藏历年(公历1027年为藏历元年，第一饶迥火兔年）
 *
 * @author 6tail
 * @package com\tyme\rabbyung
 */
class RabByungYear extends AbstractTraditionalYear
{
    function __construct(int $year)
    {
        self::validate($year);
        parent::__construct($year);
    }

    static function validate(int $year): void
    {
        parent::validateRange($year, 1027, 9999, 'rab-byung year');
    }

    static function fromSixtyCycle(int $rabByungIndex, SixtyCycle $sixtyCycle): static
    {
        return static::fromYear(1024 + $rabByungIndex * 60 + $sixtyCycle->getIndex());
    }

    static function fromElementZodiac(int $rabByungIndex, RabByungElement $element, Zodiac $zodiac): static
    {
        return static::fromSixtyCycle($rabByungIndex, SixtyCycle::fromIndex(6 * ($element->getIndex() * 2 + $zodiac->getIndex() % 2) - 5 * $zodiac->getIndex()));
    }

    static function fromYear(int $year): static
    {
        return new static($year);
    }

    /**
     * 饶迥序号
     *
     * @return int 数字，从0开始
     */
    function getRabByungIndex(): int
    {
        return intval(($this->year - 1024) / 60);
    }

    /**
     * 生肖
     *
     * @return Zodiac 生肖
     */
    function getZodiac(): Zodiac
    {
        return $this->getSixtyCycle()->getEarthBranch()->getZodiac();
    }

    /**
     * 五行
     *
     * @return RabByungElement 藏历五行
     */
    function getElement(): RabByungElement
    {
        return RabByungElement::fromIndex($this->getSixtyCycle()->getHeavenStem()->getElement()->getIndex());
    }

    function getName(): string
    {
        $digits = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'];
        $units = ['', '十', '百'];
        $n = $this->getRabByungIndex() + 1;
        $s = '';
        $pos = 0;
        while ($n > 0) {
            $digit = $n % 10;
            if ($digit > 0) {
                $s = $digits[$digit] . $units[$pos] . $s;
            } elseif ($s !== '') {
                $s = $digits[$digit] . $s;
            }
            $n = intval($n / 10);
            $pos++;
        }
        if (str_starts_with($s, '一十')) {
            $s = mb_substr($s, 1, null, 'UTF-8');
        }
        return sprintf('第%s饶迥%s%s年', $s, $this->getElement(), $this->getZodiac());
    }

    function next($n): static
    {
        return static::fromYear($this->year + $n);
    }

    function getLeapMonth(): int
    {
        $y = 1;
        $m = 4;
        $t = 1;
        $currentYear = $this->year;
        while ($y < $currentYear) {
            $i = $m + 31 + $t;
            $y += 2;
            $m = $i - 23;
            if ($i > 35) {
                $y += 1;
                $m -= 12;
            }
            $t = 1 - $t;
        }
        return $y == $currentYear ? $m : 0;
    }

    function getSolarYear(): SolarYear
    {
        return SolarYear::fromYear($this->year);
    }

    function getFirstMonth(): RabByungMonth
    {
        return new RabByungMonth($this->year, 1);
    }

    function getMonths(): array
    {
        $l = [];
        $leapMonth = $this->getLeapMonth();
        for ($i = 1; $i < 13; $i++) {
            $l[] = new RabByungMonth($this->year, $i);
            if ($i == $leapMonth) {
                $l[] = new RabByungMonth($this->year, -$i);
            }
        }
        return $l;
    }
}
