<?php

use com\tyme\solar\SolarMonth;
use PHPUnit\Framework\TestCase;

/**
 * 公历月测试
 * @author 6tail
 */
class SolarMonthTest extends TestCase
{
    function test0()
    {
        $m = SolarMonth::fromYm(2019, 5);
        $this->assertEquals('5月', $m->getName());
        $this->assertEquals('2019年5月', $m->__toString());
    }

    function test1()
    {
        $m = SolarMonth::fromYm(2023, 1);
        $this->assertEquals(5, $m->getWeekCount(0));
        $this->assertEquals(6, $m->getWeekCount(1));
        $this->assertEquals(6, $m->getWeekCount(2));
        $this->assertEquals(5, $m->getWeekCount(3));
        $this->assertEquals(5, $m->getWeekCount(4));
        $this->assertEquals(5, $m->getWeekCount(5));
        $this->assertEquals(5, $m->getWeekCount(6));
    }

    function test2()
    {
        $m = SolarMonth::fromYm(2023, 2);
        $this->assertEquals(5, $m->getWeekCount(0));
        $this->assertEquals(5, $m->getWeekCount(1));
        $this->assertEquals(5, $m->getWeekCount(2));
        $this->assertEquals(4, $m->getWeekCount(3));
        $this->assertEquals(5, $m->getWeekCount(4));
        $this->assertEquals(5, $m->getWeekCount(5));
        $this->assertEquals(5, $m->getWeekCount(6));
    }

    function test3()
    {
        $m = SolarMonth::fromYm(2023, 10)->next(1);
        $this->assertEquals('11月', $m->getName());
        $this->assertEquals('2023年11月', $m->__toString());
    }

    function test4()
    {
        $m = SolarMonth::fromYm(2023, 10);
        $this->assertEquals('2023年12月', $m->next(2)->__toString());
        $this->assertEquals('2024年1月', $m->next(3)->__toString());
        $this->assertEquals('2023年5月', $m->next(-5)->__toString());
        $this->assertEquals('2023年1月', $m->next(-9)->__toString());
        $this->assertEquals('2022年12月', $m->next(-10)->__toString());
        $this->assertEquals('2025年10月', $m->next(24)->__toString());
        $this->assertEquals('2021年10月', $m->next(-24)->__toString());
    }
}
