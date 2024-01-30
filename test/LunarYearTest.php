<?php

use com\tyme\lunar\LunarYear;
use PHPUnit\Framework\TestCase;

/**
 * 农历年测试
 * @author 6tail
 */
class LunarYearTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('农历癸卯年', LunarYear::fromYear(2023)->getName());
    }

    function test1()
    {
        $this->assertEquals('农历戊申年', LunarYear::fromYear(2023)->next(5)->getName());
    }

    function test2()
    {
        $this->assertEquals('农历戊戌年', LunarYear::fromYear(2023)->next(-5)->getName());
    }

    /**
     * 农历年的干支
     */
    function test3()
    {
        $this->assertEquals('庚子', LunarYear::fromYear(2020)->getSixtyCycle()->getName());
    }

    /**
     * 农历年的生肖(农历年->干支->地支->生肖)
     */
    function test4()
    {
        $this->assertEquals('虎', LunarYear::fromYear(1986)->getSixtyCycle()->getEarthBranch()->getZodiac()->getName());
    }

    function test5()
    {
        $this->assertEquals(12, LunarYear::fromYear(151)->getLeapMonth());
    }

    function test6()
    {
        $this->assertEquals(1, LunarYear::fromYear(2357)->getLeapMonth());
    }

    function test7()
    {
        $y = LunarYear::fromYear(2023);
        $this->assertEquals('癸卯', $y->getSixtyCycle()->getName());
        $this->assertEquals('兔', $y->getSixtyCycle()->getEarthBranch()->getZodiac()->getName());
    }

    function test8()
    {
        $this->assertEquals('上元', LunarYear::fromYear(1864)->getTwenty()->getSixty()->getName());
    }

    function test9()
    {
        $this->assertEquals('上元', LunarYear::fromYear(1923)->getTwenty()->getSixty()->getName());
    }

    function test10()
    {
        $this->assertEquals('中元', LunarYear::fromYear(1924)->getTwenty()->getSixty()->getName());
    }

    function test11()
    {
        $this->assertEquals('中元', LunarYear::fromYear(1983)->getTwenty()->getSixty()->getName());
    }

    function test12()
    {
        $this->assertEquals('下元', LunarYear::fromYear(1984)->getTwenty()->getSixty()->getName());
    }

    function test13()
    {
        $this->assertEquals('下元', LunarYear::fromYear(2043)->getTwenty()->getSixty()->getName());
    }

    function test14()
    {
        $this->assertEquals('一运', LunarYear::fromYear(1864)->getTwenty()->getName());
    }

    function test15()
    {
        $this->assertEquals('一运', LunarYear::fromYear(1883)->getTwenty()->getName());
    }

    function test16()
    {
        $this->assertEquals('二运', LunarYear::fromYear(1884)->getTwenty()->getName());
    }

    function test17()
    {
        $this->assertEquals('二运', LunarYear::fromYear(1903)->getTwenty()->getName());
    }

    function test18()
    {
        $this->assertEquals('三运', LunarYear::fromYear(1904)->getTwenty()->getName());
    }

    function test19()
    {
        $this->assertEquals('三运', LunarYear::fromYear(1923)->getTwenty()->getName());
    }

    function test20()
    {
        $this->assertEquals('八运', LunarYear::fromYear(2004)->getTwenty()->getName());
    }

    function test21()
    {
        $year = LunarYear::fromYear(1);
        $this->assertEquals('六运', $year->getTwenty()->getName());
        $this->assertEquals('中元', $year->getTwenty()->getSixty()->getName());
    }

    function test22()
    {
        $year = LunarYear::fromYear(1863);
        $this->assertEquals('九运', $year->getTwenty()->getName());
        $this->assertEquals('下元', $year->getTwenty()->getSixty()->getName());
    }
}
