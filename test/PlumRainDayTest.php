<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 梅雨天测试
 * @author 6tail
 */
class PlumRainDayTest extends TestCase
{

    function test0()
    {
        $d = SolarDay::fromYmd(2024, 6, 10)->getPlumRainDay();
        $this->assertNull($d);
    }

    function test1()
    {
        $d = SolarDay::fromYmd(2024, 6, 11)->getPlumRainDay();
        $this->assertEquals('入梅', $d->getName());
        $this->assertEquals('入梅', $d->getPlumRain()->__toString());
        $this->assertEquals('入梅第1天', $d->__toString());
    }

    function test2()
    {
        $d = SolarDay::fromYmd(2024, 7, 6)->getPlumRainDay();
        $this->assertEquals('出梅', $d->getName());
        $this->assertEquals('出梅', $d->getPlumRain()->__toString());
        $this->assertEquals('出梅', $d->__toString());
    }

    function test3()
    {
        $d = SolarDay::fromYmd(2024, 7, 5)->getPlumRainDay();
        $this->assertEquals('入梅', $d->getName());
        $this->assertEquals('入梅', $d->getPlumRain()->__toString());
        $this->assertEquals('入梅第25天', $d->__toString());
    }
}
