<?php

use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 黄道黑道十二神测试
 * @author 6tail
 */
class EclipticTest extends TestCase
{
    function test0()
    {
        $star = SolarDay::fromYmd(2023, 10, 30)->getLunarDay()->getTwelveStar();
        $this->assertEquals('天德', $star->getName());
        $this->assertEquals('黄道', $star->getEcliptic()->getName());
        $this->assertEquals('吉', $star->getEcliptic()->getLuck()->getName());
    }

    function test1()
    {
        $star = SolarDay::fromYmd(2023, 10, 19)->getLunarDay()->getTwelveStar();
        $this->assertEquals('白虎', $star->getName());
        $this->assertEquals('黑道', $star->getEcliptic()->getName());
        $this->assertEquals('凶', $star->getEcliptic()->getLuck()->getName());
    }

    function test2()
    {
        $star = SolarDay::fromYmd(2023, 10, 7)->getLunarDay()->getTwelveStar();
        $this->assertEquals('天牢', $star->getName());
        $this->assertEquals('黑道', $star->getEcliptic()->getName());
        $this->assertEquals('凶', $star->getEcliptic()->getLuck()->getName());
    }

    function test3()
    {
        $star = SolarDay::fromYmd(2023, 10, 8)->getLunarDay()->getTwelveStar();
        $this->assertEquals('玉堂', $star->getName());
        $this->assertEquals('黄道', $star->getEcliptic()->getName());
        $this->assertEquals('吉', $star->getEcliptic()->getLuck()->getName());
    }
}
