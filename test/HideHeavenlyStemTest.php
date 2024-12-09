<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 人元司令分野测试
 * @author 6tail
 */
class HideHeavenlyStemTest extends TestCase
{
    function test0()
    {
        $d = SolarDay::fromYmd(2024, 12, 4)->getHideHeavenStemDay();
        $this->assertEquals('本气', $d->getHideHeavenStem()->getType()->getName());
        $this->assertEquals('壬', $d->getHideHeavenStem()->getName());
        $this->assertEquals('壬', $d->getHideHeavenStem()->__toString());
        $this->assertEquals('水', $d->getHideHeavenStem()->getHeavenStem()->getElement()->getName());

        $this->assertEquals('壬水', $d->getName());
        $this->assertEquals(15, $d->getDayIndex());
        $this->assertEquals('壬水第16天', $d->__toString());
    }

    function test1()
    {
        $d = SolarDay::fromYmd(2024, 11, 7)->getHideHeavenStemDay();
        $this->assertEquals('余气', $d->getHideHeavenStem()->getType()->getName());
        $this->assertEquals('戊', $d->getHideHeavenStem()->getName());
        $this->assertEquals('戊', $d->getHideHeavenStem()->__toString());
        $this->assertEquals('土', $d->getHideHeavenStem()->getHeavenStem()->getElement()->getName());

        $this->assertEquals('戊土', $d->getName());
        $this->assertEquals(0, $d->getDayIndex());
        $this->assertEquals('戊土第1天', $d->__toString());
    }
}
