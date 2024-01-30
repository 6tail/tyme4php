<?php

use com\tyme\festival\LunarFestival;
use com\tyme\lunar\LunarDay;
use PHPUnit\Framework\TestCase;

/**
 * 农历传统节日测试
 * @author 6tail
 */
class LunarFestivalTest extends TestCase
{
    function test0()
    {
        for ($i = 0, $j = count(LunarFestival::$NAMES); $i < $j; $i++) {
            $f = LunarFestival::fromIndex(2023, $i);
            $this->assertNotNull($f);
            $this->assertEquals(LunarFestival::$NAMES[$i], $f->getName());
        }
    }

    function test1()
    {
        $f = LunarFestival::fromIndex(2023, 0);
        $this->assertNotNull($f);
        for ($i = 0, $j = count(LunarFestival::$NAMES); $i < $j; $i++) {
            $this->assertEquals(LunarFestival::$NAMES[$i], $f->next($i)->getName());
        }
    }

    function test2()
    {
        $f = LunarFestival::fromIndex(2023, 0);
        $this->assertNotNull($f);
        $this->assertEquals('农历甲辰年正月初一 春节', $f->next(13)->__toString());
        $this->assertEquals('农历壬寅年十一月廿九 冬至节', $f->next(-3)->__toString());
    }

    function test3()
    {
        $f = LunarFestival::fromIndex(2023, 0);
        $this->assertNotNull($f);
        $this->assertEquals('农历壬寅年三月初五 清明节', $f->next(-9)->__toString());
    }

    function test4()
    {
        $f = LunarDay::fromYmd(2010, 1, 15)->getFestival();
        $this->assertNotNull($f);
        $this->assertEquals('农历庚寅年正月十五 元宵节', $f->__toString());
    }

    function test5()
    {
        $f = LunarDay::fromYmd(2021, 12, 29)->getFestival();
        $this->assertNotNull($f);
        $this->assertEquals('农历辛丑年十二月廿九 除夕', $f->__toString());
    }
}
