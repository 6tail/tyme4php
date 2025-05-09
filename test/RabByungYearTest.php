<?php

use com\tyme\culture\Zodiac;
use com\tyme\rabbyung\RabByungElement;
use com\tyme\rabbyung\RabByungYear;
use PHPUnit\Framework\TestCase;

class RabByungYearTest extends TestCase
{

    public function test0()
    {
        $y = RabByungYear::fromElementZodiac(0, RabByungElement::fromName('火'), Zodiac::fromName('兔'));
        $this->assertEquals('第一饶迥火兔年', $y->getName());
        $this->assertEquals('1027年', $y->getSolarYear()->getName());
        $this->assertEquals('丁卯', $y->getSixtyCycle()->getName());
        $this->assertEquals(10, $y->getLeapMonth());
    }

    public function test1()
    {
        $this->assertEquals('第一饶迥火兔年', RabByungYear::fromYear(1027)->getName());
    }

    public function test2()
    {
        $this->assertEquals('第十七饶迥铁虎年', RabByungYear::fromYear(2010)->getName());
    }

    public function test3()
    {
        $this->assertEquals(5, RabByungYear::fromYear(2043)->getLeapMonth());
        $this->assertEquals(0, RabByungYear::fromYear(2044)->getLeapMonth());
        $this->assertEquals(4, RabByungYear::fromYear(1951)->getLeapMonth());
    }

    public function test4()
    {
        $this->assertEquals('第十六饶迥铁牛年', RabByungYear::fromYear(1961)->getName());
    }
}
