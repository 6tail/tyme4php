<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 神煞测试
 * @author 6tail
 */
class GodTest extends TestCase
{
    function test0()
    {
        $lunar = SolarDay::fromYmd(2004, 2, 16)->getLunarDay();
        $gods = $lunar->getGods();
        $ji = array();
        foreach ($gods as $god) {
            if ('吉' == $god->getLuck()->getName()) {
                $ji[] = $god->getName();
            }
        }

        $xiong = array();
        foreach ($gods as $god) {
            if ('凶' == $god->getLuck()->getName()) {
                $xiong[] = $god->getName();
            }
        }
        $this->assertEquals(array('天恩', '续世', '明堂'), $ji);
        $this->assertEquals(array('月煞', '月虚', '血支', '天贼', '五虚', '土符', '归忌', '血忌'), $xiong);
    }


    function test1()
    {
        $lunar = SolarDay::fromYmd(2029, 11, 16)->getLunarDay();
        $gods = $lunar->getGods();
        $ji = array();
        foreach ($gods as $god) {
            if ('吉' == $god->getLuck()->getName()) {
                $ji[] = $god->getName();
            }
        }

        $xiong = array();
        foreach ($gods as $god) {
            if ('凶' == $god->getLuck()->getName()) {
                $xiong[] = $god->getName();
            }
        }
        $this->assertEquals(array('天德合', '月空', '天恩', '益后', '金匮'), $ji);
        $this->assertEquals(array('月煞', '月虚', '血支', '五虚'), $xiong);
    }


    function test2()
    {
        $lunar = SolarDay::fromYmd(1954, 7, 16)->getLunarDay();
        $gods = $lunar->getGods();

        // 吉神宜趋
        $ji = array();
        foreach ($gods as $god) {
            if ('吉' == $god->getLuck()->getName()) {
                $ji[] = $god->getName();
            }
        }

        // 凶神宜忌
        $xiong = array();
        foreach ($gods as $god) {
            if ('凶' == $god->getLuck()->getName()) {
                $xiong[] = $god->getName();
            }
        }

        $this->assertEquals(array('民日', '天巫', '福德', '天仓', '不将', '续世', '除神', '鸣吠'), $ji);
        $this->assertEquals(array('劫煞', '天贼', '五虚', '五离'), $xiong);
    }


    function test3()
    {
        $lunar = SolarDay::fromYmd(2024, 12, 27)->getLunarDay();
        $gods = $lunar->getGods();
        $ji = array();
        foreach ($gods as $god) {
            if ('吉' == $god->getLuck()->getName()) {
                $ji[] = $god->getName();
            }
        }

        $xiong = array();
        foreach ($gods as $god) {
            if ('凶' == $god->getLuck()->getName()) {
                $xiong[] = $god->getName();
            }
        }
        $this->assertEquals(array('天恩', '四相', '阴德', '守日', '吉期', '六合', '普护', '宝光'), $ji);
        $this->assertEquals(array('三丧', '鬼哭'), $xiong);
    }
}
