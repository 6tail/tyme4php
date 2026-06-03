<?php

use com\tyme\hijri\HijriYear;
use PHPUnit\Framework\TestCase;

/**
 * 回历年测试
 * @author 6tail
 */
class HijriYearTest extends TestCase
{
    function test0()
    {
        $this->assertFalse(HijriYear::fromYear(1)->isLeap());
        $this->assertTrue(HijriYear::fromYear(2)->isLeap());
        $this->assertFalse(HijriYear::fromYear(0)->isLeap());
        $this->assertTrue(HijriYear::fromYear(-1)->isLeap());
    }
}
