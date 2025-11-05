<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 三柱测试
 *
 * @author 6tail
 */
class ThreePillarsTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('甲戌 甲戌 甲戌', SolarDay::fromYmd(1034, 10, 2)->getSixtyCycleDay()->getThreePillars()->getName());
    }
}
