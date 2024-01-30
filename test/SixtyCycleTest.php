<?php

use com\tyme\sixtycycle\EarthBranch;
use com\tyme\sixtycycle\HeavenStem;
use com\tyme\sixtycycle\SixtyCycle;
use PHPUnit\Framework\TestCase;

/**
 * 六十甲子测试
 * @author 6tail
 */
class SixtyCycleTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('丁丑', SixtyCycle::fromIndex(13)->getName());
    }

    function test1()
    {
        $this->assertEquals(13, SixtyCycle::fromName('丁丑')->getIndex());
    }

    /**
     * 五行
     */
    function test2()
    {
        $this->assertEquals('石榴木', SixtyCycle::fromName('辛酉')->getSound()->getName());
        $this->assertEquals('剑锋金', SixtyCycle::fromName('癸酉')->getSound()->getName());
        $this->assertEquals('平地木', SixtyCycle::fromName('己亥')->getSound()->getName());
    }

    /**
     * 旬
     */
    function test3()
    {
        $this->assertEquals('甲子', SixtyCycle::fromName('甲子')->getTen()->getName());
        $this->assertEquals('甲寅', SixtyCycle::fromName('乙卯')->getTen()->getName());
        $this->assertEquals('甲申', SixtyCycle::fromName('癸巳')->getTen()->getName());
    }

    /**
     * 旬空
     */
    function test4()
    {
        $this->assertEquals([EarthBranch::fromName('戌'), EarthBranch::fromName('亥')], SixtyCycle::fromName('甲子')->getExtraEarthBranches());
        $this->assertEquals([EarthBranch::fromName('子'), EarthBranch::fromName('丑')], SixtyCycle::fromName('乙卯')->getExtraEarthBranches());
        $this->assertEquals([EarthBranch::fromName('午'), EarthBranch::fromName('未')], SixtyCycle::fromName('癸巳')->getExtraEarthBranches());
    }

    /**
     * 地势(长生十二神)
     */
    function test5()
    {
        $this->assertEquals('长生', HeavenStem::fromName('丙')->getTerrain(EarthBranch::fromName('寅'))->getName());
        $this->assertEquals('沐浴', HeavenStem::fromName('辛')->getTerrain(EarthBranch::fromName('亥'))->getName());
    }
}
