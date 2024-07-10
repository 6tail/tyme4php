<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 公历日测试
 * @author 6tail
 */
class SolarDayTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('1日', SolarDay::fromYmd(2023, 1, 1)->getName());
        $this->assertEquals('2023年1月1日', SolarDay::fromYmd(2023, 1, 1)->__toString());
    }

    function test1()
    {
        $this->assertEquals('29日', SolarDay::fromYmd(2000, 2, 29)->getName());
        $this->assertEquals('2000年2月29日', SolarDay::fromYmd(2000, 2, 29)->__toString());
    }

    function test2()
    {
        $this->assertEquals(0, SolarDay::fromYmd(2023, 1, 1)->getIndexInYear());
        $this->assertEquals(364, SolarDay::fromYmd(2023, 12, 31)->getIndexInYear());
        $this->assertEquals(365, SolarDay::fromYmd(2020, 12, 31)->getIndexInYear());
    }

    function test3()
    {
        $this->assertEquals(0, SolarDay::fromYmd(2023, 1, 1)->subtract(SolarDay::fromYmd(2023, 1, 1)));
        $this->assertEquals(1, SolarDay::fromYmd(2023, 1, 2)->subtract(SolarDay::fromYmd(2023, 1, 1)));
        $this->assertEquals(-1, SolarDay::fromYmd(2023, 1, 1)->subtract(SolarDay::fromYmd(2023, 1, 2)));
        $this->assertEquals(31, SolarDay::fromYmd(2023, 2, 1)->subtract(SolarDay::fromYmd(2023, 1, 1)));
        $this->assertEquals(-31, SolarDay::fromYmd(2023, 1, 1)->subtract(SolarDay::fromYmd(2023, 2, 1)));
        $this->assertEquals(365, SolarDay::fromYmd(2024, 1, 1)->subtract(SolarDay::fromYmd(2023, 1, 1)));
        $this->assertEquals(-365, SolarDay::fromYmd(2023, 1, 1)->subtract(SolarDay::fromYmd(2024, 1, 1)));
        $this->assertEquals(1, SolarDay::fromYmd(1582, 10, 15)->subtract(SolarDay::fromYmd(1582, 10, 4)));
    }

    function test4()
    {
        $this->assertEquals('1582年10月4日', SolarDay::fromYmd(1582, 10, 15)->next(-1)->__toString());
    }

    function test5()
    {
        $this->assertEquals('2000年3月1日', SolarDay::fromYmd(2000, 2, 28)->next(2)->__toString());
    }

    function test6()
    {
        $this->assertEquals('农历庚子年闰四月初二', SolarDay::fromYmd(2020, 5, 24)->getLunarDay()->__toString());
    }

    function test7()
    {
        $this->assertEquals(31, SolarDay::fromYmd(2020, 5, 24)->subtract(SolarDay::fromYmd(2020, 4, 23)));
    }

    function test8()
    {
        $this->assertEquals('农历丙子年十一月十二', SolarDay::fromYmd(16, 11, 30)->getLunarDay()->__toString());
    }

    function test9()
    {
        $this->assertEquals('霜降', SolarDay::fromYmd(2023, 10, 27)->getTerm()->__toString());
    }

    function test10()
    {
        $this->assertEquals('豺乃祭兽第4天', SolarDay::fromYmd(2023, 10, 27)->getPhenologyDay()->__toString());
    }

    function test11()
    {
        $this->assertEquals('初候', SolarDay::fromYmd(2023, 10, 27)->getPhenologyDay()->getPhenology()->getThreePhenology()->__toString());
    }

    function test22()
    {
        $this->assertEquals('甲辰', SolarDay::fromYmd(2024, 2, 10)->getLunarDay()->getLunarMonth()->getLunarYear()->getSixtyCycle()->getName());
    }

    function test23()
    {
        $this->assertEquals('癸卯', SolarDay::fromYmd(2024, 2, 9)->getLunarDay()->getLunarMonth()->getLunarYear()->getSixtyCycle()->getName());
    }

    function test24()
    {
        // 扩展方法
        SolarDay::extend('myMethod', function () {
            return sprintf('%04d-%02d-%02d', $this->getYear(), $this->getMonth(), $this->getDay());
        });

        $this->assertEquals('2024-02-09', SolarDay::fromYmd(2024, 2, 9)->myMethod());
    }
}
