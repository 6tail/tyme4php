<?php

use com\tyme\lunar\LunarDay;
use com\tyme\lunar\LunarHour;
use com\tyme\lunar\LunarMonth;
use com\tyme\lunar\LunarYear;
use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 九星测试
 * @author 6tail
 */
class NineStarTest extends TestCase
{
    function test0()
    {
        $nineStar = LunarYear::fromYear(1985)->getNineStar();
        $this->assertEquals('六', $nineStar->getName());
        $this->assertEquals('六白金', $nineStar->__toString());
    }

    function test1()
    {
        $nineStar = LunarYear::fromYear(2022)->getNineStar();
        $this->assertEquals('五黄土', $nineStar->__toString());
        $this->assertEquals('玉衡', $nineStar->getDipper()->__toString());
    }

    function test2()
    {
        $nineStar = LunarYear::fromYear(2033)->getNineStar();
        $this->assertEquals('三碧木', $nineStar->__toString());
        $this->assertEquals('天玑', $nineStar->getDipper()->__toString());
    }

    function test3()
    {
        $nineStar = LunarMonth::fromYm(1985, 2)->getNineStar();
        $this->assertEquals('四绿木', $nineStar->__toString());
        $this->assertEquals('天权', $nineStar->getDipper()->__toString());
    }

    function test4()
    {
        $nineStar = LunarMonth::fromYm(1985, 2)->getNineStar();
        $this->assertEquals('四绿木', $nineStar->__toString());
        $this->assertEquals('天权', $nineStar->getDipper()->__toString());
    }

    function test5()
    {
        $nineStar = LunarMonth::fromYm(2022, 1)->getNineStar();
        $this->assertEquals('二黑土', $nineStar->__toString());
        $this->assertEquals('天璇', $nineStar->getDipper()->__toString());
    }

    function test6()
    {
        $nineStar = LunarMonth::fromYm(2033, 1)->getNineStar();
        $this->assertEquals('五黄土', $nineStar->__toString());
        $this->assertEquals('玉衡', $nineStar->getDipper()->__toString());
    }

    function test7()
    {
        $nineStar = SolarDay::fromYmd(1985, 2, 19)->getLunarDay()->getNineStar();
        $this->assertEquals('五黄土', $nineStar->__toString());
        $this->assertEquals('玉衡', $nineStar->getDipper()->__toString());
    }

    function test8()
    {
        $nineStar = LunarDay::fromYmd(2022, 1, 1)->getNineStar();
        $this->assertEquals('四绿木', $nineStar->__toString());
        $this->assertEquals('天权', $nineStar->getDipper()->__toString());
    }

    function test9()
    {
        $nineStar = LunarDay::fromYmd(2033, 1, 1)->getNineStar();
        $this->assertEquals('一白水', $nineStar->__toString());
        $this->assertEquals('天枢', $nineStar->getDipper()->__toString());
    }

    function test10()
    {
        $nineStar = LunarHour::fromYmdHms(2033, 1, 1, 12, 0, 0)->getNineStar();
        $this->assertEquals('七赤金', $nineStar->__toString());
        $this->assertEquals('摇光', $nineStar->getDipper()->__toString());
    }

    function test11()
    {
        $nineStar = LunarHour::fromYmdHms(2011, 5, 3, 23, 0, 0)->getNineStar();
        $this->assertEquals('七赤金', $nineStar->__toString());
        $this->assertEquals('摇光', $nineStar->getDipper()->__toString());
    }
}
