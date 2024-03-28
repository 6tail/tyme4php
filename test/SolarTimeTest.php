<?php

use com\tyme\solar\SolarTime;
use PHPUnit\Framework\TestCase;

/**
 * 公历时刻测试
 * @author 6tail
 */
class SolarTimeTest extends TestCase
{
    function test0()
    {
        $time = SolarTime::fromYmdHms(2023, 1, 1, 13, 5, 20);
        $this->assertEquals('13:05:20', $time->getName());
        $this->assertEquals('13:04:59', $time->next(-21)->getName());
    }

    function test1()
    {
        $time = SolarTime::fromYmdHms(2023, 1, 1, 13, 5, 20);
        $this->assertEquals('13:05:20', $time->getName());
        $this->assertEquals('14:06:01', $time->next(3641)->getName());
    }
}
