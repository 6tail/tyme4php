<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 方位测试
 * @author 6tail
 */
class DirectionTest extends TestCase
{
    /**
     * 福神方位
     */
    function test1()
    {
        $this->assertEquals('东南', SolarDay::fromYmd(2021, 11, 13)->getLunarDay()->getSixtyCycle()->getHeavenStem()->getMascotDirection()->getName());
    }

    /**
     * 福神方位
     */
    function test2()
    {
        $this->assertEquals('东南', SolarDay::fromYmd(2024, 1, 1)->getLunarDay()->getSixtyCycle()->getHeavenStem()->getMascotDirection()->getName());
    }

    /**
     * 太岁方位
     */
    function test3()
    {
        $this->assertEquals('东', SolarDay::fromYmd(2023, 11, 6)->getLunarDay()->getJupiterDirection()->getName());
    }

}
