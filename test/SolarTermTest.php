<?php

use com\tyme\solar\SolarDay;
use com\tyme\solar\SolarTerm;
use PHPUnit\Framework\TestCase;

/**
 * 节气测试
 * @author 6tail
 */
class SolarTermTest extends TestCase
{
    function test0()
    {
        // 冬至在去年，2022-12-22 05:48:11
        $dongZhi = SolarTerm::fromName(2023, '冬至');
        $this->assertEquals('冬至', $dongZhi->getName());
        $this->assertEquals(0, $dongZhi->getIndex());
        // 儒略日
        // 公历日
        $this->assertEquals('2022年12月22日', $dongZhi->getJulianDay()->getSolarDay()->__toString());
        $this->assertEquals('2022年12月22日', $dongZhi->getSolarDay()->__toString());

        // 冬至顺推23次，就是大雪 2023-12-07 17:32:55
        $daXue = $dongZhi->next(23);
        $this->assertEquals('大雪', $daXue->getName());
        $this->assertEquals(23, $daXue->getIndex());
        $this->assertEquals('2023年12月7日', $daXue->getJulianDay()->getSolarDay()->__toString());
        $this->assertEquals('2023年12月7日', $daXue->getSolarDay()->__toString());

        // 冬至逆推2次，就是上一年的小雪 2022-11-22 16:20:28
        $xiaoXue = $dongZhi->next(-2);
        $this->assertEquals('小雪', $xiaoXue->getName());
        $this->assertEquals(22, $xiaoXue->getIndex());
        $this->assertEquals('2022年11月22日', $xiaoXue->getJulianDay()->getSolarDay()->__toString());
        $this->assertEquals('2022年11月22日', $xiaoXue->getSolarDay()->__toString());

        // 冬至顺推24次，就是下一个冬至 2023-12-22 11:27:20
        $dongZhi2 = $dongZhi->next(24);
        $this->assertEquals('冬至', $dongZhi2->getName());
        $this->assertEquals(0, $dongZhi2->getIndex());
        $this->assertEquals('2023年12月22日', $dongZhi2->getJulianDay()->getSolarDay()->__toString());
        $this->assertEquals('2023年12月22日', $dongZhi2->getSolarDay()->__toString());
    }

    function test1()
    {
        // 公历2023年的雨水，2023-02-19 06:34:16
        $jq = SolarTerm::fromName(2023, '雨水');
        $this->assertEquals('雨水', $jq->getName());
        $this->assertEquals(4, $jq->getIndex());
    }

    function test2()
    {
        // 公历2023年的大雪，2023-12-07 17:32:55
        $jq = SolarTerm::fromName(2023, '大雪');
        $this->assertEquals('大雪', $jq->getName());
        // 索引
        $this->assertEquals(23, $jq->getIndex());
        // 公历
        $this->assertEquals('2023年12月7日', $jq->getJulianDay()->getSolarDay()->__toString());
        $this->assertEquals('2023年12月7日', $jq->getSolarDay()->__toString());
        // 农历
        $this->assertEquals('农历癸卯年十月廿五', $jq->getJulianDay()->getSolarDay()->getLunarDay()->__toString());
        // 推移
        $this->assertEquals('雨水', $jq->next(5)->getName());
    }

    function test3()
    {
        $this->assertEquals('寒露', SolarDay::fromYmd(2023, 10, 10)->getTerm()->getName());
    }

    function test4()
    {
        // 大雪当天
        $this->assertEquals('大雪第1天', SolarDay::fromYmd(2023, 12, 7)->getTermDay()->__toString());
        // 天数索引
        $this->assertEquals(0, SolarDay::fromYmd(2023, 12, 7)->getTermDay()->getDayIndex());

        $this->assertEquals('大雪第2天', SolarDay::fromYmd(2023, 12, 8)->getTermDay()->__toString());
        $this->assertEquals('大雪第15天', SolarDay::fromYmd(2023, 12, 21)->getTermDay()->__toString());

        $this->assertEquals('冬至第1天', SolarDay::fromYmd(2023, 12, 22)->getTermDay()->__toString());
    }

    function test5()
    {
        $this->assertEquals('1034年10月1日', SolarTerm::fromName(1034, '寒露')->getSolarDay()->__toString());
        $this->assertEquals('1034年10月3日', SolarTerm::fromName(1034, '寒露')->getJulianDay()->getSolarDay()->__toString());
        $this->assertEquals('1034年10月3日 06:02:28', SolarTerm::fromName(1034, '寒露')->getJulianDay()->getSolarTime()->__toString());
    }
}
