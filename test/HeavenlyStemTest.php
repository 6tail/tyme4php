<?php

use com\tyme\sixtycycle\HeavenStem;
use PHPUnit\Framework\TestCase;

/**
 * 天干测试
 * @author 6tail
 */
class HeavenlyStemTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('甲', HeavenStem::fromIndex(0)->getName());
    }

    function test1()
    {
        $this->assertEquals(0, HeavenStem::fromName('甲')->getIndex());
    }

    /**
     * 天干的五行生克
     */
    function test2()
    {
        $this->assertEquals(HeavenStem::fromName('丙')->getElement(), HeavenStem::fromName('甲')->getElement()->getReinforce());
    }

    /**
     * 十神
     */
    function test3()
    {
        $this->assertEquals('比肩', HeavenStem::fromName('甲')->getTenStar(HeavenStem::fromName('甲'))->getName());
        $this->assertEquals('劫财', HeavenStem::fromName('甲')->getTenStar(HeavenStem::fromName('乙'))->getName());
        $this->assertEquals('食神', HeavenStem::fromName('甲')->getTenStar(HeavenStem::fromName('丙'))->getName());
        $this->assertEquals('伤官', HeavenStem::fromName('甲')->getTenStar(HeavenStem::fromName('丁'))->getName());
        $this->assertEquals('偏财', HeavenStem::fromName('甲')->getTenStar(HeavenStem::fromName('戊'))->getName());
        $this->assertEquals('正财', HeavenStem::fromName('甲')->getTenStar(HeavenStem::fromName('己'))->getName());
        $this->assertEquals('七杀', HeavenStem::fromName('甲')->getTenStar(HeavenStem::fromName('庚'))->getName());
        $this->assertEquals('正官', HeavenStem::fromName('甲')->getTenStar(HeavenStem::fromName('辛'))->getName());
        $this->assertEquals('偏印', HeavenStem::fromName('甲')->getTenStar(HeavenStem::fromName('壬'))->getName());
        $this->assertEquals('正印', HeavenStem::fromName('甲')->getTenStar(HeavenStem::fromName('癸'))->getName());
    }
}
