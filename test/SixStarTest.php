<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 六曜测试
 * @author 6tail
 */
class SixStarTest extends TestCase
{

    function test0()
    {
        $this->assertEquals('佛灭', SolarDay::fromYmd(2020, 4, 23)->getLunarDay()->getSixStar()->getName());
    }

    function test1()
    {
        $this->assertEquals('友引', SolarDay::fromYmd(2021, 1, 15)->getLunarDay()->getSixStar()->getName());
    }

    function test2()
    {
        $this->assertEquals('先胜', SolarDay::fromYmd(2017, 1, 5)->getLunarDay()->getSixStar()->getName());
    }

    function test3()
    {
        $this->assertEquals('友引', SolarDay::fromYmd(2020, 4, 10)->getLunarDay()->getSixStar()->getName());
    }

    function test4()
    {
        $this->assertEquals('大安', SolarDay::fromYmd(2020, 6, 11)->getLunarDay()->getSixStar()->getName());
    }

    function test5()
    {
        $this->assertEquals('先胜', SolarDay::fromYmd(2020, 6, 1)->getLunarDay()->getSixStar()->getName());
    }

    function test6()
    {
        $this->assertEquals('先负', SolarDay::fromYmd(2020, 12, 8)->getLunarDay()->getSixStar()->getName());
    }

    function test8()
    {
        $this->assertEquals('赤口', SolarDay::fromYmd(2020, 12, 11)->getLunarDay()->getSixStar()->getName());
    }
}
