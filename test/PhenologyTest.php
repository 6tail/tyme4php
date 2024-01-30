<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 物候测试
 * @author 6tail
 */
class PhenologyTest extends TestCase
{
    function test0()
    {
        $solarDay = SolarDay::fromYmd(2020, 4, 23);
        // 七十二候
        $phenology = $solarDay->getPhenologyDay();
        // 三候
        $threePhenology = $phenology->getPhenology()->getThreePhenology();
        $this->assertEquals('谷雨', $solarDay->getTerm()->getName());
        $this->assertEquals('初候', $threePhenology->getName());
        $this->assertEquals('萍始生', $phenology->getName());
        // 该候的第5天
        $this->assertEquals(4, $phenology->getDayIndex());
    }

    function test1()
    {
        $solarDay = SolarDay::fromYmd(2021, 12, 26);
        // 七十二候
        $phenology = $solarDay->getPhenologyDay();
        // 三候
        $threePhenology = $phenology->getPhenology()->getThreePhenology();
        $this->assertEquals('冬至', $solarDay->getTerm()->getName());
        $this->assertEquals('二候', $threePhenology->getName());
        $this->assertEquals('麋角解', $phenology->getName());
        // 该候的第1天
        $this->assertEquals(0, $phenology->getDayIndex());
    }
}
