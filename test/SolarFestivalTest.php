<?php

use com\tyme\festival\SolarFestival;
use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 公历现代节日测试
 * @author 6tail
 */
class SolarFestivalTest extends TestCase
{
    function test0()
    {
        for ($i = 0, $j = count(SolarFestival::$NAMES); $i < $j; $i++) {
            $f = SolarFestival::fromIndex(2023, $i);
            $this->assertNotNull($f);
            $this->assertEquals(SolarFestival::$NAMES[$i], $f->getName());
        }
    }

    function test1()
    {
        $f = SolarFestival::fromIndex(2023, 0);
        $this->assertNotNull($f);
        for ($i = 0, $j = count(SolarFestival::$NAMES); $i < $j; $i++) {
            $this->assertEquals(SolarFestival::$NAMES[$i], $f->next($i)->getName());
        }
    }

    function test2()
    {
        $f = SolarFestival::fromIndex(2023, 0);
        $this->assertNotNull($f);
        $this->assertEquals('2024年5月1日 五一劳动节', $f->next(13)->__toString());
        $this->assertEquals('2022年8月1日 八一建军节', $f->next(-3)->__toString());
    }

    function test3()
    {
        $f = SolarFestival::fromIndex(2023, 0);
        $this->assertNotNull($f);
        $this->assertEquals('2022年3月8日 三八妇女节', $f->next(-9)->__toString());
    }

    function test4()
    {
        $f = SolarDay::fromYmd(2010, 1, 1)->getFestival();
        $this->assertNotNull($f);
        $this->assertEquals('2010年1月1日 元旦', $f->__toString());
    }

    function test5()
    {
        $f = SolarDay::fromYmd(2021, 5, 4)->getFestival();
        $this->assertNotNull($f);
        $this->assertEquals('2021年5月4日 五四青年节', $f->__toString());
    }

    function test6()
    {
        $f = SolarDay::fromYmd(1939, 5, 4)->getFestival();
        $this->assertNull($f);
    }
}
