<?php

use com\tyme\solar\SolarHalfYear;
use PHPUnit\Framework\TestCase;

/**
 * 公历半年测试
 * @author 6tail
 */
class SolarHalfYearTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('上半年', SolarHalfYear::fromIndex(2023, 0)->getName());
        $this->assertEquals('2023年上半年', SolarHalfYear::fromIndex(2023, 0)->__toString());
    }

    function test1()
    {
        $this->assertEquals('下半年', SolarHalfYear::fromIndex(2023, 1)->getName());
        $this->assertEquals('2023年下半年', SolarHalfYear::fromIndex(2023, 1)->__toString());
    }

    function test2()
    {
        $this->assertEquals('下半年', SolarHalfYear::fromIndex(2023, 0)->next(1)->getName());
        $this->assertEquals('2023年下半年', SolarHalfYear::fromIndex(2023, 0)->next(1)->__toString());
    }

    function test3()
    {
        $this->assertEquals('上半年', SolarHalfYear::fromIndex(2023, 0)->next(2)->getName());
        $this->assertEquals('2024年上半年', SolarHalfYear::fromIndex(2023, 0)->next(2)->__toString());
    }

    function test4()
    {
        $this->assertEquals('上半年', SolarHalfYear::fromIndex(2023, 0)->next(-2)->getName());
        $this->assertEquals('2022年上半年', SolarHalfYear::fromIndex(2023, 0)->next(-2)->__toString());
    }

    function test5()
    {
        $this->assertEquals('2021年上半年', SolarHalfYear::fromIndex(2023, 0)->next(-4)->__toString());
        $this->assertEquals('2021年下半年', SolarHalfYear::fromIndex(2023, 0)->next(-3)->__toString());
    }
}
