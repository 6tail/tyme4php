<?php

use com\tyme\lunar\LunarDay;
use PHPUnit\Framework\TestCase;

/**
 * 农历日测试
 * @author 6tail
 */
class LunarDayTest extends TestCase
{
    function test1()
    {
        $this->assertEquals('1年1月1日', LunarDay::fromYmd(0, 11, 18)->getSolarDay()->__toString());
    }

    function test2()
    {
        $this->assertEquals('9999年12月31日', LunarDay::fromYmd(9999, 12, 2)->getSolarDay()->__toString());
    }

    function test3()
    {
        $this->assertEquals('1905年2月4日', LunarDay::fromYmd(1905, 1, 1)->getSolarDay()->__toString());
    }

    function test4()
    {
        $this->assertEquals('2039年1月23日', LunarDay::fromYmd(2038, 12, 29)->getSolarDay()->__toString());
    }

    function test5()
    {
        $this->assertEquals('1500年1月31日', LunarDay::fromYmd(1500, 1, 1)->getSolarDay()->__toString());
    }

    function test6()
    {
        $this->assertEquals('1501年1月18日', LunarDay::fromYmd(1500, 12, 29)->getSolarDay()->__toString());
    }

    function test7()
    {
        $this->assertEquals('1582年10月4日', LunarDay::fromYmd(1582, 9, 18)->getSolarDay()->__toString());
    }

    function test8()
    {
        $this->assertEquals('1582年10月15日', LunarDay::fromYmd(1582, 9, 19)->getSolarDay()->__toString());
    }

    function test9()
    {
        $this->assertEquals('2020年1月6日', LunarDay::fromYmd(2019, 12, 12)->getSolarDay()->__toString());
    }

    function test10()
    {
        $this->assertEquals('2033年12月22日', LunarDay::fromYmd(2033, -11, 1)->getSolarDay()->__toString());
    }

    function test11()
    {
        $this->assertEquals('2021年7月16日', LunarDay::fromYmd(2021, 6, 7)->getSolarDay()->__toString());
    }

    function test12()
    {
        $this->assertEquals('2034年2月19日', LunarDay::fromYmd(2034, 1, 1)->getSolarDay()->__toString());
    }

    function test13()
    {
        $this->assertEquals('2034年1月20日', LunarDay::fromYmd(2033, 12, 1)->getSolarDay()->__toString());
    }

    function test14()
    {
        $this->assertEquals('7013年12月24日', LunarDay::fromYmd(7013, -11, 4)->getSolarDay()->__toString());
    }

    function test15()
    {
        $this->assertEquals('己亥', LunarDay::fromYmd(2023, 8, 24)->getSixtyCycle()->__toString());
    }

    function test16()
    {
        $this->assertEquals('癸酉', LunarDay::fromYmd(1653, 1, 6)->getSixtyCycle()->__toString());
    }

    function test17()
    {
        $this->assertEquals('农历庚寅年二月初二', LunarDay::fromYmd(2010, 1, 1)->next(31)->__toString());
    }

    function test18()
    {
        $this->assertEquals('农历壬辰年闰四月初一', LunarDay::fromYmd(2012, 3, 1)->next(60)->__toString());
    }

    function test19()
    {
        $this->assertEquals('农历壬辰年闰四月廿九', LunarDay::fromYmd(2012, 3, 1)->next(88)->__toString());
    }

    function test20()
    {
        $this->assertEquals('农历壬辰年五月初一', LunarDay::fromYmd(2012, 3, 1)->next(89)->__toString());
    }

    function test21()
    {
        $this->assertEquals('2020年4月23日', LunarDay::fromYmd(2020, 4, 1)->getSolarDay()->__toString());
    }

    function test22()
    {
        $this->assertEquals('甲辰', LunarDay::fromYmd(2024, 1, 1)->getMonth()->getYear()->getSixtyCycle()->getName());
    }

    function test23()
    {
        $this->assertEquals('癸卯', LunarDay::fromYmd(2023, 12, 30)->getMonth()->getYear()->getSixtyCycle()->getName());
    }

    /**
     * 二十八宿
     */
    function test24()
    {
        $d = LunarDay::fromYmd(2020, 4, 13);
        $star = $d->getTwentyEightStar();
        $this->assertEquals('南', $star->getZone()->getName());
        $this->assertEquals('朱雀', $star->getZone()->getBeast()->getName());
        $this->assertEquals('翼', $star->getName());
        $this->assertEquals('火', $star->getSevenStar()->getName());
        $this->assertEquals('蛇', $star->getAnimal()->getName());
        $this->assertEquals('凶', $star->getLuck()->getName());

        $this->assertEquals('阳天', $star->getLand()->getName());
        $this->assertEquals('东南', $star->getLand()->getDirection()->getName());
    }

    function test25()
    {
        $d = LunarDay::fromYmd(2023, 9, 28);
        $star = $d->getTwentyEightStar();
        $this->assertEquals('南', $star->getZone()->getName());
        $this->assertEquals('朱雀', $star->getZone()->getBeast()->getName());
        $this->assertEquals('柳', $star->getName());
        $this->assertEquals('土', $star->getSevenStar()->getName());
        $this->assertEquals('獐', $star->getAnimal()->getName());
        $this->assertEquals('凶', $star->getLuck()->getName());

        $this->assertEquals('炎天', $star->getLand()->getName());
        $this->assertEquals('南', $star->getLand()->getDirection()->getName());
    }

    function test26()
    {
        $lunar = LunarDay::fromYmd(2005, 11, 23);
        $this->assertEquals('戊子', $lunar->getMonth()->getSixtyCycle()->getName());
        $this->assertEquals('戊子', $lunar->getMonthSixtyCycle()->getName());
    }
}
