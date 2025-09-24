<?php

use com\tyme\culture\Phase;
use com\tyme\lunar\LunarDay;
use com\tyme\solar\SolarDay;
use com\tyme\solar\SolarTime;
use PHPUnit\Framework\TestCase;

/**
 * 月相测试
 * @author 6tail
 */
class PhaseTest extends TestCase
{

    function test0()
    {
        $phase = Phase::fromName(2025, 7, '下弦月');
        $this->assertEquals('2025年9月14日 18:32:57', $phase->getSolarTime()->__toString());
    }

    function test1()
    {
        $phase = Phase::fromIndex(2025, 7, 6);
        $this->assertEquals('2025年9月14日 18:32:57', $phase->getSolarTime()->__toString());
    }

    function test2()
    {
        $phase = Phase::fromIndex(2025, 7, 8);
        $this->assertEquals('2025年9月22日 03:54:07', $phase->getSolarTime()->__toString());
    }

    function test3()
    {
        $phase = SolarDay::fromYmd(2025, 9, 21)->getPhase();
        $this->assertEquals('残月', $phase->__toString());
    }

    function test4()
    {
        $phase = LunarDay::fromYmd(2025, 7, 30)->getPhase();
        $this->assertEquals('残月', $phase->__toString());
    }

    function test5()
    {
        $phase = SolarTime::fromYmdHms(2025, 9, 22, 4, 0, 0)->getPhase();
        $this->assertEquals('蛾眉月', $phase->__toString());
    }

    function test6()
    {
        $phase = SolarTime::fromYmdHms(2025, 9, 22, 3, 0, 0)->getPhase();
        $this->assertEquals('残月', $phase->__toString());
    }

    function test7()
    {
        $d = SolarDay::fromYmd(2023, 9, 15)->getPhaseDay();
        $this->assertEquals('新月第1天', $d->__toString());
    }

    function test8()
    {
        $d = SolarDay::fromYmd(2023, 9, 17)->getPhaseDay();
        $this->assertEquals('蛾眉月第2天', $d->__toString());
    }

    function test9()
    {
        $phase = SolarTime::fromYmdHms(2025, 9, 22, 3, 54, 7)->getPhase();
        $this->assertEquals('新月', $phase->__toString());
    }

    function test10()
    {
        $phase = SolarTime::fromYmdHms(2025, 9, 22, 3, 54, 6)->getPhase();
        $this->assertEquals('残月', $phase->__toString());
    }

    function test11()
    {
        $phase = SolarTime::fromYmdHms(2025, 9, 22, 3, 54, 8)->getPhase();
        $this->assertEquals('蛾眉月', $phase->__toString());
    }

}
