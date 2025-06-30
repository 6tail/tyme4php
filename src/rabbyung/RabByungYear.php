<?php

namespace com\tyme\rabbyung;

use com\tyme\AbstractTyme;
use com\tyme\culture\Zodiac;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\solar\SolarYear;
use InvalidArgumentException;

/**
 * 藏历年
 * @author 6tail
 * @package com\tyme\rabbyung
 */
class RabByungYear extends AbstractTyme
{
    protected int $rabByungIndex;
    protected SixtyCycle $sixtyCycle;

    function __construct(int $rabByungIndex, SixtyCycle $sixtyCycle)
    {
        if ($rabByungIndex < 0 || $rabByungIndex > 150) {
            throw new InvalidArgumentException(sprintf('illegal rab-byung index: %d', $rabByungIndex));
        }
        $this->rabByungIndex = $rabByungIndex;
        $this->sixtyCycle = $sixtyCycle;
    }

    static function fromSixtyCycle(int $rabByungIndex, SixtyCycle $sixtyCycle): static
    {
        return new static($rabByungIndex, $sixtyCycle);
    }

    static function fromElementZodiac(int $rabByungIndex, RabByungElement $element, Zodiac $zodiac): static
    {
        for ($i = 0; $i < 60; $i++) {
            $sc = SixtyCycle::fromIndex($i);
            if ($sc->getEarthBranch()->getZodiac()->equals($zodiac) && $sc->getHeavenStem()->getElement()->getIndex() == $element->getIndex()) {
                return new static($rabByungIndex, $sc);
            }
        }
        throw new InvalidArgumentException(sprintf('illegal rab-byung element %s, zodiac %s', $element, $zodiac));
    }

    static function fromYear(int $year): static
    {
        return new static(intval(($year - 1024) / 60), SixtyCycle::fromIndex($year - 4));
    }

    function getRabByungIndex(): int
    {
        return $this->rabByungIndex;
    }

    function getSixtyCycle(): SixtyCycle
    {
        return $this->sixtyCycle;
    }

    function getZodiac(): Zodiac
    {
        return $this->sixtyCycle->getEarthBranch()->getZodiac();
    }

    function getElement(): RabByungElement
    {
        return RabByungElement::fromIndex($this->sixtyCycle->getHeavenStem()->getElement()->getIndex());
    }

    function getName(): string
    {
        $digits = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'];
        $units = ['', '十', '百'];
        $n = $this->rabByungIndex + 1;
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
        return static::fromYear($this->getYear() + $n);
    }

    function getYear(): int
    {
        return 1024 + $this->rabByungIndex * 60 + $this->sixtyCycle->getIndex();
    }

    function getLeapMonth(): int
    {
        $y = 1;
        $m = 4;
        $t = 0;
        $currentYear = $this->getYear();
        while ($y < $currentYear) {
            $i = $m - 1 + ($t % 2 == 0 ? 33 : 32);
            $y = intval(($y * 12 + $i) / 12);
            $m = $i % 12 + 1;
            $t++;
        }
        return $y == $currentYear ? $m : 0;
    }

    function getSolarYear(): SolarYear
    {
        return SolarYear::fromYear($this->getYear());
    }

    function getFirstMonth(): RabByungMonth
    {
        return new RabByungMonth($this, 1);
    }

    function getMonthCount(): int
    {
        return $this->getLeapMonth() < 1 ? 12 : 13;
    }

    function getMonths(): array
    {
        $l = [];
        $leapMonth = $this->getLeapMonth();
        for ($i = 1; $i < 13; $i++) {
            $l[] = new RabByungMonth($this, $i);
            if ($i == $leapMonth) {
                $l[] = new RabByungMonth($this, -$i);
            }
        }
        return $l;
    }
}
