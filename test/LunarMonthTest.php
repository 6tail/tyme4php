<?php

use com\tyme\lunar\LunarDay;
use com\tyme\lunar\LunarMonth;
use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 农历月测试
 * @author 6tail
 */
class LunarMonthTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('七月', LunarMonth::fromYm(2359, 7)->getName());
    }

    /**
     * 闰月
     */

    function test1()
    {
        $this->assertEquals('闰七月', LunarMonth::fromYm(2359, -7)->getName());
    }

    function test2()
    {
        $this->assertEquals(29, LunarMonth::fromYm(2023, 6)->getDayCount());
    }

    function test3()
    {
        $this->assertEquals(30, LunarMonth::fromYm(2023, 7)->getDayCount());
    }

    function test4()
    {
        $this->assertEquals(30, LunarMonth::fromYm(2023, 8)->getDayCount());
    }

    function test5()
    {
        $this->assertEquals(29, LunarMonth::fromYm(2023, 9)->getDayCount());
    }

    function test6()
    {
        $this->assertEquals('2023年10月15日', LunarMonth::fromYm(2023, 9)->getFirstJulianDay()->getSolarDay()->__toString());
    }

    function test7()
    {
        $this->assertEquals('甲寅', LunarMonth::fromYm(2023, 1)->getSixtyCycle()->getName());
    }

    function test8()
    {
        $this->assertEquals('丙辰', LunarMonth::fromYm(2023, -2)->getSixtyCycle()->getName());
    }

    function test9()
    {
        $this->assertEquals('丁巳', LunarMonth::fromYm(2023, 3)->getSixtyCycle()->getName());
    }

    function test10()
    {
        $this->assertEquals('丙寅', LunarMonth::fromYm(2024, 1)->getSixtyCycle()->getName());
    }

    function test11()
    {
        $this->assertEquals('丙寅', LunarMonth::fromYm(2023, 12)->getSixtyCycle()->getName());
    }

    function test12()
    {
        $this->assertEquals('壬寅', LunarMonth::fromYm(2022, 1)->getSixtyCycle()->getName());
    }

    function test13()
    {
        $this->assertEquals('闰十二月', LunarMonth::fromYm(37, -12)->getName());
    }

    function test14()
    {
        $this->assertEquals('闰十二月', LunarMonth::fromYm(5552, -12)->getName());
    }

    function test15()
    {
        $this->assertEquals('农历戊子年十二月', LunarMonth::fromYm(2008, 11)->next(1)->__toString());
    }

    function test16()
    {
        $this->assertEquals('农历己丑年正月', LunarMonth::fromYm(2008, 11)->next(2)->__toString());
    }

    function test17()
    {
        $this->assertEquals('农历己丑年五月', LunarMonth::fromYm(2008, 11)->next(6)->__toString());
    }

    function test18()
    {
        $this->assertEquals('农历己丑年闰五月', LunarMonth::fromYm(2008, 11)->next(7)->__toString());
    }

    function test19()
    {
        $this->assertEquals('农历己丑年六月', LunarMonth::fromYm(2008, 11)->next(8)->__toString());
    }

    function test20()
    {
        $this->assertEquals('农历庚寅年正月', LunarMonth::fromYm(2008, 11)->next(15)->__toString());
    }

    function test21()
    {
        $this->assertEquals('农历戊子年十一月', LunarMonth::fromYm(2008, 12)->next(-1)->__toString());
    }

    function test22()
    {
        $this->assertEquals('农历戊子年十一月', LunarMonth::fromYm(2009, 1)->next(-2)->__toString());
    }

    function test23()
    {
        $this->assertEquals('农历戊子年十一月', LunarMonth::fromYm(2009, 5)->next(-6)->__toString());
    }

    function test24()
    {
        $this->assertEquals('农历戊子年十一月', LunarMonth::fromYm(2009, -5)->next(-7)->__toString());
    }

    function test25()
    {
        $this->assertEquals('农历戊子年十一月', LunarMonth::fromYm(2009, 6)->next(-8)->__toString());
    }

    function test26()
    {
        $this->assertEquals('农历戊子年十一月', LunarMonth::fromYm(2010, 1)->next(-15)->__toString());
    }

    function test27()
    {
        $this->assertEquals(29, LunarMonth::fromYm(2012, -4)->getDayCount());
    }

    function test28()
    {
        $this->assertEquals('癸亥', LunarMonth::fromYm(2023, 9)->getSixtyCycle()->__toString());
    }

    function test29()
    {
        $d = SolarDay::fromYmd(2023, 10, 7)->getLunarDay();
        $this->assertEquals('壬戌', $d->getLunarMonth()->getSixtyCycle()->__toString());
        $this->assertEquals('辛酉', $d->getMonthSixtyCycle()->__toString());
    }

    function test30()
    {
        $d = SolarDay::fromYmd(2023, 10, 8)->getLunarDay();
        $this->assertEquals('壬戌', $d->getLunarMonth()->getSixtyCycle()->__toString());
        $this->assertEquals('壬戌', $d->getMonthSixtyCycle()->__toString());
    }

    function test31()
    {
        $d = SolarDay::fromYmd(2023, 10, 15)->getLunarDay();
        $this->assertEquals('九月', $d->getLunarMonth()->getName());
        $this->assertEquals('癸亥', $d->getLunarMonth()->getSixtyCycle()->__toString());
        $this->assertEquals('壬戌', $d->getMonthSixtyCycle()->__toString());
    }

    function test32()
    {
        $d = SolarDay::fromYmd(2023, 11, 7)->getLunarDay();
        $this->assertEquals('癸亥', $d->getLunarMonth()->getSixtyCycle()->__toString());
        $this->assertEquals('壬戌', $d->getMonthSixtyCycle()->__toString());
    }

    function test33()
    {
        $d = SolarDay::fromYmd(2023, 11, 8)->getLunarDay();
        $this->assertEquals('癸亥', $d->getLunarMonth()->getSixtyCycle()->__toString());
        $this->assertEquals('癸亥', $d->getMonthSixtyCycle()->__toString());
    }

    function test34()
    {
        // 2023年闰2月
        $m = LunarMonth::fromYm(2023, 12);
        $this->assertEquals('农历癸卯年十二月', $m->__toString());
        $this->assertEquals('农历癸卯年十一月', $m->next(-1)->__toString());
        $this->assertEquals('农历癸卯年十月', $m->next(-2)->__toString());
    }

    function test35()
    {
        // 2023年闰2月
        $m = LunarMonth::fromYm(2023, 3);
        $this->assertEquals('农历癸卯年三月', $m->__toString());
        $this->assertEquals('农历癸卯年闰二月', $m->next(-1)->__toString());
        $this->assertEquals('农历癸卯年二月', $m->next(-2)->__toString());
        $this->assertEquals('农历癸卯年正月', $m->next(-3)->__toString());
        $this->assertEquals('农历壬寅年十二月', $m->next(-4)->__toString());
        $this->assertEquals('农历壬寅年十一月', $m->next(-5)->__toString());
    }

    function test36()
    {
        $d = SolarDay::fromYmd(1983, 2, 15)->getLunarDay();
        $this->assertEquals('甲寅', $d->getLunarMonth()->getSixtyCycle()->__toString());
        $this->assertEquals('甲寅', $d->getMonthSixtyCycle()->__toString());
    }

    function test37()
    {
        $d = SolarDay::fromYmd(2023, 10, 30)->getLunarDay();
        $this->assertEquals('癸亥', $d->getLunarMonth()->getSixtyCycle()->__toString());
        $this->assertEquals('壬戌', $d->getMonthSixtyCycle()->__toString());
    }

    function test38()
    {
        $d = SolarDay::fromYmd(2023, 10, 19)->getLunarDay();
        $this->assertEquals('癸亥', $d->getLunarMonth()->getSixtyCycle()->__toString());
        $this->assertEquals('壬戌', $d->getMonthSixtyCycle()->__toString());
    }

    function test39()
    {
        $m = LunarMonth::fromYm(2023, 11);
        $this->assertEquals('农历癸卯年十一月', $m->__toString());
        $this->assertEquals('乙丑', $m->getSixtyCycle()->__toString());
    }

    function test40()
    {
        $this->assertEquals('庚申', LunarDay::fromYmd(2018, 6, 26)->getMonthSixtyCycle()->__toString());
    }

    function test41()
    {
        $this->assertEquals('辛丑', LunarMonth::fromYm(1991, 12)->getSixtyCycle()->__toString());
    }

}
