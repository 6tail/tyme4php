<?php

use com\tyme\solar\SolarYear;
use PHPUnit\Framework\TestCase;

/**
 * 公历年测试
 * @author 6tail
 */
class SolarYearTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('2023年', SolarYear::fromYear(2023)->getName());
    }

    function test1()
    {
        $this->assertFalse(SolarYear::fromYear(2023)->isLeap());
    }

    function test2()
    {
        $this->assertTrue(SolarYear::fromYear(1500)->isLeap());
    }

    function test3()
    {
        $this->assertFalse(SolarYear::fromYear(1700)->isLeap());
    }

    function test4()
    {
        $this->assertEquals(365, SolarYear::fromYear(2023)->getDayCount());
    }

    function test5()
    {
        $this->assertEquals('2028年', SolarYear::fromYear(2023)->next(5)->getName());
    }

    function test6()
    {
        $this->assertEquals('2018年', SolarYear::fromYear(2023)->next(-5)->getName());
    }
}
