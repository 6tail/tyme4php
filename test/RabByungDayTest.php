<?php

use com\tyme\culture\Zodiac;
use com\tyme\rabbyung\RabByungDay;
use com\tyme\rabbyung\RabByungElement;
use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

class RabByungDayTest extends TestCase
{

    public function test0()
    {
        $this->assertEquals('第十六饶迥铁虎年十二月初一', SolarDay::fromYmd(1951, 1, 8)->getRabByungDay()->__toString());
        $this->assertEquals('1951年1月8日', RabByungDay::fromElementZodiac(15, RabByungElement::fromName('铁'), Zodiac::fromName('虎'), 12, 1)->getSolarDay()->__toString());
    }

    public function test1()
    {
        $this->assertEquals('第十八饶迥铁马年十二月三十', SolarDay::fromYmd(2051, 2, 11)->getRabByungDay()->__toString());
        $this->assertEquals('2051年2月11日', RabByungDay::fromElementZodiac(17, RabByungElement::fromName('铁'), Zodiac::fromName('马'), 12, 30)->getSolarDay()->__toString());
    }

    public function test2()
    {
        $this->assertEquals('第十七饶迥木蛇年二月廿五', SolarDay::fromYmd(2025, 4, 23)->getRabByungDay()->__toString());
        $this->assertEquals('2025年4月23日', RabByungDay::fromElementZodiac(16, RabByungElement::fromName('木'), Zodiac::fromName('蛇'), 2, 25)->getSolarDay()->__toString());
    }

    public function test3()
    {
        $this->assertEquals('第十六饶迥铁兔年正月初二', SolarDay::fromYmd(1951, 2, 8)->getRabByungDay()->__toString());
        $this->assertEquals('1951年2月8日', RabByungDay::fromElementZodiac(15, RabByungElement::fromName('铁'), Zodiac::fromName('兔'), 1, 2)->getSolarDay()->__toString());
    }

    public function test4()
    {
        $this->assertEquals('第十六饶迥铁虎年十二月闰十六', SolarDay::fromYmd(1951, 1, 24)->getRabByungDay()->__toString());
        $this->assertEquals('1951年1月24日', RabByungDay::fromElementZodiac(15, RabByungElement::fromName('铁'), Zodiac::fromName('虎'), 12, -16)->getSolarDay()->__toString());
    }

    public function test5()
    {
        $this->assertEquals('第十六饶迥铁牛年五月十一', SolarDay::fromYmd(1961, 6, 24)->getRabByungDay()->__toString());
        $this->assertEquals('1961年6月24日', RabByungDay::fromElementZodiac(15, RabByungElement::fromName('铁'), Zodiac::fromName('牛'), 5, 11)->getSolarDay()->__toString());
    }

    public function test6()
    {
        $this->assertEquals('第十六饶迥铁兔年十二月廿八', SolarDay::fromYmd(1952, 2, 23)->getRabByungDay()->__toString());
        $this->assertEquals('1952年2月23日', RabByungDay::fromElementZodiac(15, RabByungElement::fromName('铁'), Zodiac::fromName('兔'), 12, 28)->getSolarDay()->__toString());
    }

    public function test7()
    {
        $this->assertEquals('第十七饶迥木蛇年二月廿九', SolarDay::fromYmd(2025, 4, 26)->getRabByungDay()->__toString());
    }

    public function test8()
    {
        $this->assertEquals('第十七饶迥木蛇年二月廿七', SolarDay::fromYmd(2025, 4, 25)->getRabByungDay()->__toString());
    }
}