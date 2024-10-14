<?php

use com\tyme\lunar\LunarHour;
use PHPUnit\Framework\TestCase;

/**
 * 时辰测试
 * @author 6tail
 */
class LunarHourTest extends TestCase
{
    function test1()
    {
        $h = LunarHour::fromYmdHms(2020, -4, 5, 23, 0, 0);
        $this->assertEquals('子时', $h->getName());
        $this->assertEquals('农历庚子年闰四月初五戊子时', $h->__toString());
    }

    function test2()
    {
        $h = LunarHour::fromYmdHms(2020, -4, 5, 0, 59, 0);
        $this->assertEquals('子时', $h->getName());
        $this->assertEquals('农历庚子年闰四月初五丙子时', $h->__toString());
    }

    function test3()
    {
        $h = LunarHour::fromYmdHms(2020, -4, 5, 1, 0, 0);
        $this->assertEquals('丑时', $h->getName());
        $this->assertEquals('农历庚子年闰四月初五丁丑时', $h->__toString());
    }

    function test4()
    {
        $h = LunarHour::fromYmdHms(2020, -4, 5, 21, 30, 0);
        $this->assertEquals('亥时', $h->getName());
        $this->assertEquals('农历庚子年闰四月初五丁亥时', $h->__toString());
    }

    function test5()
    {
        $h = LunarHour::fromYmdHms(2020, -4, 2, 23, 30, 0);
        $this->assertEquals('子时', $h->getName());
        $this->assertEquals('农历庚子年闰四月初二壬子时', $h->__toString());
    }

    function test6()
    {
        $h = LunarHour::fromYmdHms(2020, 4, 28, 23, 30, 0);
        $this->assertEquals('子时', $h->getName());
        $this->assertEquals('农历庚子年四月廿八甲子时', $h->__toString());
    }

    function test7()
    {
        $h = LunarHour::fromYmdHms(2020, 4, 29, 0, 0, 0);
        $this->assertEquals('子时', $h->getName());
        $this->assertEquals('农历庚子年四月廿九甲子时', $h->__toString());
    }

    function test8()
    {
        $h = LunarHour::fromYmdHms(2023, 11, 14, 23, 0, 0);
        $this->assertEquals('甲子', $h->getSixtyCycle()->getName());

        $this->assertEquals('己未', $h->getDaySixtyCycle()->getName());
        $this->assertEquals('戊午', $h->getLunarDay()->getSixtyCycle()->getName());
        $this->assertEquals('农历癸卯年十一月十四', $h->getLunarDay()->__toString());

        $this->assertEquals('甲子', $h->getMonthSixtyCycle()->getName());
        $this->assertEquals('农历癸卯年十一月', $h->getLunarDay()->getLunarMonth()->__toString());
        $this->assertEquals('乙丑', $h->getLunarDay()->getLunarMonth()->getSixtyCycle()->getName());

        $this->assertEquals('癸卯', $h->getYearSixtyCycle()->getName());
        $this->assertEquals('农历癸卯年', $h->getLunarDay()->getLunarMonth()->getLunarYear()->__toString());
        $this->assertEquals('癸卯', $h->getLunarDay()->getLunarMonth()->getLunarYear()->getSixtyCycle()->getName());
    }

    function test9()
    {
        $h = LunarHour::fromYmdHms(2023, 11, 14, 6, 0, 0);
        $this->assertEquals('乙卯', $h->getSixtyCycle()->getName());

        $this->assertEquals('戊午', $h->getDaySixtyCycle()->getName());
        $this->assertEquals('戊午', $h->getLunarDay()->getSixtyCycle()->getName());
        $this->assertEquals('农历癸卯年十一月十四', $h->getLunarDay()->__toString());

        $this->assertEquals('甲子', $h->getMonthSixtyCycle()->getName());
        $this->assertEquals('农历癸卯年十一月', $h->getLunarDay()->getLunarMonth()->__toString());
        $this->assertEquals('乙丑', $h->getLunarDay()->getLunarMonth()->getSixtyCycle()->getName());

        $this->assertEquals('癸卯', $h->getYearSixtyCycle()->getName());
        $this->assertEquals('农历癸卯年', $h->getLunarDay()->getLunarMonth()->getLunarYear()->__toString());
        $this->assertEquals('癸卯', $h->getLunarDay()->getLunarMonth()->getLunarYear()->getSixtyCycle()->getName());
    }

    function test28()
    {
        $h = LunarHour::fromYmdHms(2024, 9, 7, 10, 0, 0);
        $this->assertEquals('留连', $h->getMinorRen()->getName());
    }
}
