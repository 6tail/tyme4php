<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 建除十二值神测试
 * @author 6tail
 */
class DutyTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('闭', SolarDay::fromYmd(2023, 10, 30)->getLunarDay()->getDuty()->getName());
    }

    function test1()
    {
        $this->assertEquals('建', SolarDay::fromYmd(2023, 10, 19)->getLunarDay()->getDuty()->getName());
    }

    function test2()
    {
        $this->assertEquals('除', SolarDay::fromYmd(2023, 10, 7)->getLunarDay()->getDuty()->getName());
    }

    function test3()
    {
        $this->assertEquals('除', SolarDay::fromYmd(2023, 10, 8)->getLunarDay()->getDuty()->getName());
    }
}
