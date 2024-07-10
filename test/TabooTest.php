<?php

use com\tyme\solar\SolarDay;
use com\tyme\solar\SolarTime;
use PHPUnit\Framework\TestCase;

/**
 * 宜忌测试
 * @author 6tail
 */
class TabooTest extends TestCase
{
    function test0()
    {
        $taboos = array();
        foreach (SolarDay::fromYmd(2024, 6, 26)->getLunarDay()->getRecommends() as $t) {
            $taboos[] = $t->getName();
        }

        $this->assertEquals(array('嫁娶', '祭祀', '理发', '作灶', '修饰垣墙', '平治道涂', '整手足甲', '沐浴', '冠笄'), $taboos);
    }


    function test1()
    {
        $taboos = array();
        foreach (SolarDay::fromYmd(2024, 6, 26)->getLunarDay()->getAvoids() as $t) {
            $taboos[] = $t->getName();
        }

        $this->assertEquals(array('破土', '出行', '栽种'), $taboos);
    }


    function test2()
    {
        $taboos = array();
        foreach (SolarTime::fromYmdHms(2024, 6, 25, 4, 0, 0)->getLunarHour()->getRecommends() as $t) {
            $taboos[] = $t->getName();
        }

        $this->assertEquals(array(), $taboos);
    }


    function test3()
    {
        $taboos = array();
        foreach (SolarTime::fromYmdHms(2024, 6, 25, 4, 0, 0)->getLunarHour()->getAvoids() as $t) {
            $taboos[] = $t->getName();
        }

        $this->assertEquals(array('诸事不宜'), $taboos);
    }


    function test4()
    {
        $taboos = array();
        foreach (SolarTime::fromYmdHms(2024, 4, 22, 0, 0, 0)->getLunarHour()->getRecommends() as $t) {
            $taboos[] = $t->getName();
        }

        $this->assertEquals(array('嫁娶', '交易', '开市', '安床', '祭祀', '求财'), $taboos);
    }


    function test5()
    {
        $taboos = array();
        foreach (SolarTime::fromYmdHms(2024, 4, 22, 0, 0, 0)->getLunarHour()->getAvoids() as $t) {
            $taboos[] = $t->getName();
        }

        $this->assertEquals(array('出行', '移徙', '赴任', '词讼', '祈福', '修造', '求嗣'), $taboos);
    }


    function test6()
    {
        $taboos = array();
        foreach (SolarDay::fromYmd(2021, 3, 7)->getLunarDay()->getRecommends() as $t) {
            $taboos[] = $t->getName();
        }
        $this->assertEquals(array('裁衣', '经络', '伐木', '开柱眼', '拆卸', '修造', '动土', '上梁', '合脊', '合寿木', '入殓', '除服', '成服', '移柩', '破土', '安葬', '启钻', '修坟', '立碑'), $taboos);
    }
}
