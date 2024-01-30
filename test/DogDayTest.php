<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 三伏测试
 * @author 6tail
 */
class DogDayTest extends TestCase
{
    function test0()
    {
        $d = SolarDay::fromYmd(2011, 7, 14)->getDogDay();
        $this->assertEquals('初伏', $d->getName());
        $this->assertEquals('初伏', $d->getDog()->__toString());
        $this->assertEquals('初伏第1天', $d->__toString());
    }


    function test1()
    {
        $d = SolarDay::fromYmd(2011, 7, 23)->getDogDay();
        $this->assertEquals('初伏', $d->getName());
        $this->assertEquals('初伏', $d->getDog()->__toString());
        $this->assertEquals('初伏第10天', $d->__toString());
    }


    function test2()
    {
        $d = SolarDay::fromYmd(2011, 7, 24)->getDogDay();
        $this->assertEquals('中伏', $d->getName());
        $this->assertEquals('中伏', $d->getDog()->__toString());
        $this->assertEquals('中伏第1天', $d->__toString());
    }


    function test3()
    {
        $d = SolarDay::fromYmd(2011, 8, 12)->getDogDay();
        $this->assertEquals('中伏', $d->getName());
        $this->assertEquals('中伏', $d->getDog()->__toString());
        $this->assertEquals('中伏第20天', $d->__toString());
    }


    function test4()
    {
        $d = SolarDay::fromYmd(2011, 8, 13)->getDogDay();
        $this->assertEquals('末伏', $d->getName());
        $this->assertEquals('末伏', $d->getDog()->__toString());
        $this->assertEquals('末伏第1天', $d->__toString());
    }


    function test5()
    {
        $d = SolarDay::fromYmd(2011, 8, 22)->getDogDay();
        $this->assertEquals('末伏', $d->getName());
        $this->assertEquals('末伏', $d->getDog()->__toString());
        $this->assertEquals('末伏第10天', $d->__toString());
    }


    function test6()
    {
        $this->assertNull(SolarDay::fromYmd(2011, 7, 13)->getDogDay());
    }


    function test7()
    {
        $this->assertNull(SolarDay::fromYmd(2011, 8, 23)->getDogDay());
    }


    function test8()
    {
        $d = SolarDay::fromYmd(2012, 7, 18)->getDogDay();
        $this->assertEquals('初伏', $d->getName());
        $this->assertEquals('初伏', $d->getDog()->__toString());
        $this->assertEquals('初伏第1天', $d->__toString());
    }


    function test9()
    {
        $d = SolarDay::fromYmd(2012, 8, 5)->getDogDay();
        $this->assertEquals('中伏', $d->getName());
        $this->assertEquals('中伏', $d->getDog()->__toString());
        $this->assertEquals('中伏第9天', $d->__toString());
    }


    function test10()
    {
        $d = SolarDay::fromYmd(2012, 8, 8)->getDogDay();
        $this->assertEquals('末伏', $d->getName());
        $this->assertEquals('末伏', $d->getDog()->__toString());
        $this->assertEquals('末伏第2天', $d->__toString());
    }
}
