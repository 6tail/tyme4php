<?php

namespace com\tyme\rabbyung;

use com\tyme\AbstractTyme;
use com\tyme\culture\Zodiac;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\solar\SolarYear;
use InvalidArgumentException;

/**
 * 藏历年(公历1027年为藏历元年，第一饶迥火兔年）
 * @author 6tail
 * @package com\tyme\rabbyung
 */
class RabByungYear extends AbstractTyme
{
    /**
     * @var int 饶迥(胜生周)序号，从0开始
     */
    protected int $rabByungIndex;

    /**
     * @var int 五行索引，从0开始
     */
    protected int $elementIndex;

    /**
     * @var int 生肖索引，从0开始
     */
    protected int $zodiacIndex;

    function __construct(int $rabByungIndex, int $elementIndex, int $zodiacIndex)
    {
        if ($rabByungIndex < 0 || $rabByungIndex > 150) {
            throw new InvalidArgumentException(sprintf('illegal rab-byung index: %d', $rabByungIndex));
        }
        if ($elementIndex < 0 || $elementIndex >= count(RabByungElement::$NAMES)) {
            throw new InvalidArgumentException(sprintf('illegal element index: %d', $elementIndex));
        }
        if ($zodiacIndex < 0 || $zodiacIndex >= count(Zodiac::$NAMES)) {
            throw new InvalidArgumentException(sprintf('illegal zodiac index: %d', $zodiacIndex));
        }
        $this->rabByungIndex = $rabByungIndex;
        $this->elementIndex = $elementIndex;
        $this->zodiacIndex = $zodiacIndex;
    }

    static function validate(int $year): void
    {
        if ($year < 1027 || $year > 9999) {
            throw new InvalidArgumentException(sprintf('illegal rab-byung year: %d', $year));
        }
    }

    static function fromSixtyCycle(int $rabByungIndex, SixtyCycle $sixtyCycle): static
    {
        return new static($rabByungIndex, $sixtyCycle->getHeavenStem()->getElement()->getIndex(), $sixtyCycle->getEarthBranch()->getZodiac()->getIndex());
    }

    static function fromElementZodiac(int $rabByungIndex, RabByungElement $element, Zodiac $zodiac): static
    {
        return new static($rabByungIndex, $element->getIndex(), $zodiac->getIndex());
    }

    static function fromYear(int $year): static
    {
        return static::fromSixtyCycle(intval(($year - 1024) / 60), SixtyCycle::fromIndex($year - 4));
    }

    function getRabByungIndex(): int
    {
        return $this->rabByungIndex;
    }

    function getSixtyCycle(): SixtyCycle
    {
        return SixtyCycle::fromIndex(6 * ($this->elementIndex * 2 + $this->zodiacIndex % 2) - 5 * $this->zodiacIndex);
    }

    function getZodiac(): Zodiac
    {
        return Zodiac::fromIndex($this->zodiacIndex);
    }

    function getElement(): RabByungElement
    {
        return RabByungElement::fromIndex($this->elementIndex);
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
        return 1024 + $this->rabByungIndex * 60 + $this->getSixtyCycle()->getIndex();
    }

    function getLeapMonth(): int
    {
        $y = 1;
        $m = 4;
        $t = 1;
        $currentYear = $this->getYear();
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
        return SolarYear::fromYear($this->getYear());
    }

    function getFirstMonth(): RabByungMonth
    {
        return new RabByungMonth($this->getYear(), 1);
    }

    function getMonthCount(): int
    {
        return $this->getLeapMonth() < 1 ? 12 : 13;
    }

    function getMonths(): array
    {
        $l = [];
        $y = $this->getYear();
        $leapMonth = $this->getLeapMonth();
        for ($i = 1; $i < 13; $i++) {
            $l[] = new RabByungMonth($y, $i);
            if ($i == $leapMonth) {
                $l[] = new RabByungMonth($y, -$i);
            }
        }
        return $l;
    }
}
