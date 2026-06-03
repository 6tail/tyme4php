<?php

use com\tyme\hijri\HijriDay;
use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 回历日测试
 * @author 6tail
 */
class HijriDayTest extends TestCase
{

    function test0()
    {
        $this->assertEquals("1年穆哈兰姆月1日", SolarDay::fromYmd(622, 7, 16)->getHijriDay()->__toString());
    }


    function test1()
    {
        $this->assertEquals("1447年都尔喀尔德月26日", SolarDay::fromYmd(2026, 5, 13)->getHijriDay()->__toString());
        $this->assertEquals("2026年5月13日", HijriDay::fromYmd(1447, 11, 26)->getSolarDay()->__toString());
    }


    function test2()
    {
        $this->assertEquals("-538年都尔黑哲月12日", SolarDay::fromYmd(100, 7, 8)->getHijriDay()->__toString());
        $this->assertEquals("100年7月8日", HijriDay::fromYmd(-538, 12, 12)->getSolarDay()->__toString());
    }


    function test3()
    {
        $this->assertEquals("0年都尔黑哲月29日", SolarDay::fromYmd(622, 7, 15)->getHijriDay()->__toString());
        $this->assertEquals("622年7月15日", HijriDay::fromYmd(0, 12, 29)->getSolarDay()->__toString());
    }


    function test4()
    {
        $this->assertEquals("-640年主马达·敖外鲁月16日", SolarDay::fromYmd(1, 1, 1)->getHijriDay()->__toString());
        $this->assertEquals("1年1月1日", HijriDay::fromYmd(-640, 5, 16)->getSolarDay()->__toString());
    }


    function test5()
    {
        $this->assertEquals("9666年赖比尔·阿色尼月2日", SolarDay::fromYmd(9999, 12, 31)->getHijriDay()->__toString());
        $this->assertEquals("9999年12月31日", HijriDay::fromYmd(9666, 4, 2)->getSolarDay()->__toString());
    }
}
