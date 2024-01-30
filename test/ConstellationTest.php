<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 星座测试
 * @author 6tail
 */
class ConstellationTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('白羊', SolarDay::fromYmd(2020, 3, 21)->getConstellation()->getName());
        $this->assertEquals('白羊', SolarDay::fromYmd(2020, 4, 19)->getConstellation()->getName());
    }

    function test1()
    {
        $this->assertEquals('金牛', SolarDay::fromYmd(2020, 4, 20)->getConstellation()->getName());
        $this->assertEquals('金牛', SolarDay::fromYmd(2020, 5, 20)->getConstellation()->getName());
    }

    function test2()
    {
        $this->assertEquals('双子', SolarDay::fromYmd(2020, 5, 21)->getConstellation()->getName());
        $this->assertEquals('双子', SolarDay::fromYmd(2020, 6, 21)->getConstellation()->getName());
    }

    function test3()
    {
        $this->assertEquals('巨蟹', SolarDay::fromYmd(2020, 6, 22)->getConstellation()->getName());
        $this->assertEquals('巨蟹', SolarDay::fromYmd(2020, 7, 22)->getConstellation()->getName());
    }
}
