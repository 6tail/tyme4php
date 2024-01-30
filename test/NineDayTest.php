<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 数九测试
 * @author 6tail
 */
class NineDayTest extends TestCase
{
    function test0()
    {
        $d = SolarDay::fromYmd(2020, 12, 21)->getNineDay();
        $this->assertEquals('一九', $d->getName());
        $this->assertEquals('一九', $d->getNine()->__toString());
        $this->assertEquals('一九第1天', $d->__toString());
    }

    function test1()
    {
        $d = SolarDay::fromYmd(2020, 12, 22)->getNineDay();
        $this->assertEquals('一九', $d->getName());
        $this->assertEquals('一九', $d->getNine()->__toString());
        $this->assertEquals('一九第2天', $d->__toString());
    }

    function test2()
    {
        $d = SolarDay::fromYmd(2020, 1, 7)->getNineDay();
        $this->assertEquals('二九', $d->getName());
        $this->assertEquals('二九', $d->getNine()->__toString());
        $this->assertEquals('二九第8天', $d->__toString());
    }

    function test3()
    {
        $d = SolarDay::fromYmd(2021, 1, 6)->getNineDay();
        $this->assertEquals('二九', $d->getName());
        $this->assertEquals('二九', $d->getNine()->__toString());
        $this->assertEquals('二九第8天', $d->__toString());
    }

    function test4()
    {
        $d = SolarDay::fromYmd(2021, 1, 8)->getNineDay();
        $this->assertEquals('三九', $d->getName());
        $this->assertEquals('三九', $d->getNine()->__toString());
        $this->assertEquals('三九第1天', $d->__toString());
    }

    function test5()
    {
        $d = SolarDay::fromYmd(2021, 3, 5)->getNineDay();
        $this->assertEquals('九九', $d->getName());
        $this->assertEquals('九九', $d->getNine()->__toString());
        $this->assertEquals('九九第3天', $d->__toString());
    }

    function test6()
    {
        $d = SolarDay::fromYmd(2021, 7, 5)->getNineDay();
        $this->assertNull($d);
    }
}
