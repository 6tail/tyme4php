<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 胎神测试
 * @author 6tail
 */
class ElementTest extends TestCase
{
    /**
     * 逐日胎神
     */
    function test1()
    {
        $this->assertEquals('碓磨厕 外东南', SolarDay::fromYmd(2021, 11, 13)->getLunarDay()->getFetusDay()->getName());
    }

    function test2()
    {
        $this->assertEquals('占门碓 外东南', SolarDay::fromYmd(2021, 11, 12)->getLunarDay()->getFetusDay()->getName());
    }

    function test3()
    {
        $this->assertEquals('厨灶厕 外西南', SolarDay::fromYmd(2011, 11, 12)->getLunarDay()->getFetusDay()->getName());
    }
}
