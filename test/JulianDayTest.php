<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 儒略日测试
 * @author 6tail
 */
class JulianDayTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('2023年1月1日', SolarDay::fromYmd(2023, 1, 1)->getJulianDay()->getSolarDay()->__toString());
    }
}
