<?php

use com\tyme\eightchar\ChildLimit;
use com\tyme\eightchar\EightChar;
use com\tyme\eightchar\provider\impl\China95ChildLimitProvider;
use com\tyme\eightchar\provider\impl\DefaultChildLimitProvider;
use com\tyme\eightchar\provider\impl\DefaultEightCharProvider;
use com\tyme\eightchar\provider\impl\LunarSect2EightCharProvider;
use com\tyme\enums\Gender;
use com\tyme\lunar\LunarHour;
use com\tyme\sixtycycle\HeavenStem;
use com\tyme\sixtycycle\SixtyCycle;
use com\tyme\solar\SolarTime;
use PHPUnit\Framework\TestCase;

/**
 * 八字测试
 * @author 6tail
 */
class EightCharTest extends TestCase
{
    /**
     * 十神
     */
    function test1()
    {
        // 八字
        $eightChar = new EightChar(
            SixtyCycle::fromName('丙寅'),
            SixtyCycle::fromName('癸巳'),
            SixtyCycle::fromName('癸酉'),
            SixtyCycle::fromName('己未')
        );

        // 年柱
        $year = $eightChar->getYear();
        // 月柱
        $month = $eightChar->getMonth();
        // 日柱
        $day = $eightChar->getDay();
        // 时柱
        $hour = $eightChar->getHour();

        // 日元(日主、日干)
        $me = $day->getHeavenStem();

        // 年柱天干十神
        $this->assertEquals('正财', $me->getTenStar($year->getHeavenStem())->getName());
        // 月柱天干十神
        $this->assertEquals('比肩', $me->getTenStar($month->getHeavenStem())->getName());
        // 时柱天干十神
        $this->assertEquals('七杀', $me->getTenStar($hour->getHeavenStem())->getName());

        // 年柱地支十神（本气)
        $this->assertEquals('伤官', $me->getTenStar($year->getEarthBranch()->getHideHeavenStemMain())->getName());
        // 年柱地支十神（中气)
        $this->assertEquals('正财', $me->getTenStar($year->getEarthBranch()->getHideHeavenStemMiddle())->getName());
        // 年柱地支十神（余气)
        $this->assertEquals('正官', $me->getTenStar($year->getEarthBranch()->getHideHeavenStemResidual())->getName());

        // 日柱地支十神（本气)
        $this->assertEquals('偏印', $me->getTenStar($day->getEarthBranch()->getHideHeavenStemMain())->getName());
        // 日柱地支藏干（中气)
        $this->assertNull($day->getEarthBranch()->getHideHeavenStemMiddle());
        // 日柱地支藏干（余气)
        $this->assertNull($day->getEarthBranch()->getHideHeavenStemResidual());

        // 指定任意天干的十神
        $this->assertEquals('正财', $me->getTenStar(HeavenStem::fromName('丙'))->getName());
    }

    /**
     * 地势(长生十二神)
     */
    function test2()
    {
        // 八字
        $eightChar = new EightChar(
            SixtyCycle::fromName('丙寅'),
            SixtyCycle::fromName('癸巳'),
            SixtyCycle::fromName('癸酉'),
            SixtyCycle::fromName('己未')
        );

        // 年柱
        $year = $eightChar->getYear();
        // 月柱
        $month = $eightChar->getMonth();
        // 日柱
        $day = $eightChar->getDay();
        // 时柱
        $hour = $eightChar->getHour();

        // 日元(日主、日干)
        $me = $day->getHeavenStem();

        // 年柱地势
        $this->assertEquals('沐浴', $me->getTerrain($year->getEarthBranch())->getName());
        // 月柱地势
        $this->assertEquals('胎', $me->getTerrain($month->getEarthBranch())->getName());
        // 日柱地势
        $this->assertEquals('病', $me->getTerrain($day->getEarthBranch())->getName());
        // 时柱地势
        $this->assertEquals('墓', $me->getTerrain($hour->getEarthBranch())->getName());
    }

    /**
     * 胎元/胎息/命宫
     */
    function test3()
    {
        // 八字
        $eightChar = new EightChar(
            SixtyCycle::fromName('癸卯'),
            SixtyCycle::fromName('辛酉'),
            SixtyCycle::fromName('己亥'),
            SixtyCycle::fromName('癸酉')
        );

        // 胎元
        $taiYuan = $eightChar->getFetalOrigin();
        $this->assertEquals('壬子', $taiYuan->getName());
        // 胎元纳音
        $this->assertEquals('桑柘木', $taiYuan->getSound()->getName());
    }

    /**
     * 胎息
     */
    function test4()
    {
        // 八字
        $eightChar = new EightChar(
            SixtyCycle::fromName('癸卯'),
            SixtyCycle::fromName('辛酉'),
            SixtyCycle::fromName('己亥'),
            SixtyCycle::fromName('癸酉')
        );

        // 胎息
        $taiXi = $eightChar->getFetalBreath();
        $this->assertEquals('甲寅', $taiXi->getName());
        // 胎息纳音
        $this->assertEquals('大溪水', $taiXi->getSound()->getName());
    }

    /**
     * 命宫
     */
    function test5()
    {
        // 八字
        $eightChar = new EightChar(
            SixtyCycle::fromName('癸卯'),
            SixtyCycle::fromName('辛酉'),
            SixtyCycle::fromName('己亥'),
            SixtyCycle::fromName('癸酉')
        );

        // 命宫
        $mingGong = $eightChar->getOwnSign();
        $this->assertEquals('癸亥', $mingGong->getName());
        // 命宫纳音
        $this->assertEquals('大海水', $mingGong->getSound()->getName());
    }

    /**
     * 身宫
     */
    function test6()
    {
        // 八字
        $eightChar = new EightChar(
            SixtyCycle::fromName('癸卯'),
            SixtyCycle::fromName('辛酉'),
            SixtyCycle::fromName('己亥'),
            SixtyCycle::fromName('癸酉')
        );

        // 身宫
        $shenGong = $eightChar->getBodySign();
        $this->assertEquals('己未', $shenGong->getName());
        // 身宫纳音
        $this->assertEquals('天上火', $shenGong->getSound()->getName());
    }

    /**
     * 地势(长生十二神)
     */
    function test7()
    {
        // 八字
        $eightChar = new EightChar(
            SixtyCycle::fromName('乙酉'),
            SixtyCycle::fromName('戊子'),
            SixtyCycle::fromName('辛巳'),
            SixtyCycle::fromName('壬辰')
        );

        // 日干
        $me = $eightChar->getDay()->getHeavenStem();
        // 年柱地势
        $this->assertEquals('临官', $me->getTerrain($eightChar->getYear()->getEarthBranch())->getName());
        // 月柱地势
        $this->assertEquals('长生', $me->getTerrain($eightChar->getMonth()->getEarthBranch())->getName());
        // 日柱地势
        $this->assertEquals('死', $me->getTerrain($eightChar->getDay()->getEarthBranch())->getName());
        // 时柱地势
        $this->assertEquals('墓', $me->getTerrain($eightChar->getHour()->getEarthBranch())->getName());
    }

    /**
     * 公历时刻转八字
     */
    function test8()
    {
        $eightChar = SolarTime::fromYmdHms(2005, 12, 23, 8, 37, 0)->getLunarHour()->getEightChar();
        $this->assertEquals('乙酉', $eightChar->getYear()->getName());
        $this->assertEquals('戊子', $eightChar->getMonth()->getName());
        $this->assertEquals('辛巳', $eightChar->getDay()->getName());
        $this->assertEquals('壬辰', $eightChar->getHour()->getName());
    }

    function test9()
    {
        $eightChar = SolarTime::fromYmdHms(1988, 2, 15, 23, 30, 0)->getLunarHour()->getEightChar();
        $this->assertEquals('戊辰', $eightChar->getYear()->getName());
        $this->assertEquals('甲寅', $eightChar->getMonth()->getName());
        $this->assertEquals('辛丑', $eightChar->getDay()->getName());
        $this->assertEquals('戊子', $eightChar->getHour()->getName());
    }

    /**
     * 童限测试
     */
    function test11()
    {
        $childLimit = ChildLimit::fromSolarTime(SolarTime::fromYmdHms(2022, 3, 9, 20, 51, 0), Gender::MAN);
        $this->assertEquals(8, $childLimit->getYearCount());
        $this->assertEquals(9, $childLimit->getMonthCount());
        $this->assertEquals(2, $childLimit->getDayCount());
        $this->assertEquals(10, $childLimit->getHourCount());
        $this->assertEquals(26, $childLimit->getMinuteCount());
        $this->assertEquals('2030年12月12日 07:17:00', $childLimit->getEndTime()->__toString());
    }

    /**
     * 童限测试
     */
    function test12()
    {
        $childLimit = ChildLimit::fromSolarTime(SolarTime::fromYmdHms(2018, 6, 11, 9, 30, 0), Gender::WOMAN);
        $this->assertEquals(1, $childLimit->getYearCount());
        $this->assertEquals(9, $childLimit->getMonthCount());
        $this->assertEquals(10, $childLimit->getDayCount());
        $this->assertEquals(1, $childLimit->getHourCount());
        $this->assertEquals(42, $childLimit->getMinuteCount());
        $this->assertEquals('2020年3月21日 11:12:00', $childLimit->getEndTime()->__toString());
    }

    /**
     * 大运测试
     */
    function test13()
    {
        // 童限
        $childLimit = ChildLimit::fromSolarTime(SolarTime::fromYmdHms(1983, 2, 15, 20, 0, 0), Gender::WOMAN);
        // 八字
        $this->assertEquals('癸亥 甲寅 甲戌 甲戌', $childLimit->getEightChar()->__toString());
        // 童限年数
        $this->assertEquals(6, $childLimit->getYearCount());
        // 童限月数
        $this->assertEquals(2, $childLimit->getMonthCount());
        // 童限日数
        $this->assertEquals(18, $childLimit->getDayCount());
        // 童限结束(即开始起运)的公历时刻
        $this->assertEquals('1989年5月4日 18:24:00', $childLimit->getEndTime()->__toString());
        // 童限开始(即出生)的农历年干支
        $this->assertEquals('癸亥', $childLimit->getStartTime()->getLunarHour()->getLunarDay()->getLunarMonth()->getLunarYear()->getSixtyCycle()->getName());
        // 童限结束(即开始起运)的农历年干支
        $this->assertEquals('己巳', $childLimit->getEndTime()->getLunarHour()->getLunarDay()->getLunarMonth()->getLunarYear()->getSixtyCycle()->getName());

        // 第1轮大运
        $decadeFortune = $childLimit->getStartDecadeFortune();
        // 开始年龄
        $this->assertEquals(7, $decadeFortune->getStartAge());
        // 结束年龄
        $this->assertEquals(16, $decadeFortune->getEndAge());
        // 开始年
        $this->assertEquals(1989, $decadeFortune->getStartLunarYear()->getYear());
        // 结束年
        $this->assertEquals(1998, $decadeFortune->getEndLunarYear()->getYear());
        // 干支
        $this->assertEquals('乙卯', $decadeFortune->getName());
        // 下一大运
        $this->assertEquals('丙辰', $decadeFortune->next(1)->getName());
        // 上一大运
        $this->assertEquals('甲寅', $decadeFortune->next(-1)->getName());
        // 第9轮大运
        $this->assertEquals('癸亥', $decadeFortune->next(8)->getName());

        // 小运
        $fortune = $childLimit->getStartFortune();
        // 年龄
        $this->assertEquals(7, $fortune->getAge());
        // 农历年
        $this->assertEquals(1989, $fortune->getLunarYear()->getYear());
        // 干支
        $this->assertEquals('辛巳', $fortune->getName());

        // 流年
        $this->assertEquals('己巳', $fortune->getLunarYear()->getSixtyCycle()->getName());
    }

    function test14()
    {
        // 童限
        $childLimit = ChildLimit::fromSolarTime(SolarTime::fromYmdHms(1992, 2, 2, 12, 0, 0), Gender::MAN);
        // 八字
        $this->assertEquals('辛未 辛丑 戊申 戊午', $childLimit->getEightChar()->__toString());
        // 童限年数
        $this->assertEquals(9, $childLimit->getYearCount());
        // 童限月数
        $this->assertEquals(0, $childLimit->getMonthCount());
        // 童限日数
        $this->assertEquals(9, $childLimit->getDayCount());
        // 童限结束(即开始起运)的公历时刻
        $this->assertEquals('2001年2月11日 18:58:00', $childLimit->getEndTime()->__toString());
        // 童限开始(即出生)的农历年干支
        $this->assertEquals('辛未', $childLimit->getStartTime()->getLunarHour()->getLunarDay()->getLunarMonth()->getLunarYear()->getSixtyCycle()->getName());
        // 童限结束(即开始起运)的农历年干支
        $this->assertEquals('辛巳', $childLimit->getEndTime()->getLunarHour()->getLunarDay()->getLunarMonth()->getLunarYear()->getSixtyCycle()->getName());

        // 第1轮大运
        $decadeFortune = $childLimit->getStartDecadeFortune();
        // 开始年龄
        $this->assertEquals(10, $decadeFortune->getStartAge());
        // 结束年龄
        $this->assertEquals(19, $decadeFortune->getEndAge());
        // 开始年
        $this->assertEquals(2000, $decadeFortune->getStartLunarYear()->getYear());
        // 结束年
        $this->assertEquals(2009, $decadeFortune->getEndLunarYear()->getYear());
        // 干支
        $this->assertEquals('庚子', $decadeFortune->getName());
        // 下一大运
        $this->assertEquals('己亥', $decadeFortune->next(1)->getName());

        // 小运
        $fortune = $childLimit->getStartFortune();
        // 年龄
        $this->assertEquals(10, $fortune->getAge());
        // 农历年
        $this->assertEquals(2000, $fortune->getLunarYear()->getYear());
        // 干支
        $this->assertEquals('戊申', $fortune->getName());
        // 小运推移
        $this->assertEquals('丙午', $fortune->next(2)->getName());
        $this->assertEquals('庚戌', $fortune->next(-2)->getName());

        // 流年
        $this->assertEquals('庚辰', $fortune->getLunarYear()->getSixtyCycle()->getName());
    }

    function test16()
    {
        // 童限
        $childLimit = ChildLimit::fromSolarTime(SolarTime::fromYmdHms(1990, 3, 15, 10, 30, 0), Gender::MAN);
        // 八字
        $this->assertEquals('庚午 己卯 己卯 己巳', $childLimit->getEightChar()->__toString());
        // 童限年数
        $this->assertEquals(6, $childLimit->getYearCount());
        // 童限月数
        $this->assertEquals(11, $childLimit->getMonthCount());
        // 童限日数
        $this->assertEquals(23, $childLimit->getDayCount());
        // 童限结束(即开始起运)的公历时刻
        $this->assertEquals('1997年3月11日 00:22:00', $childLimit->getEndTime()->__toString());

        // 小运
        $fortune = $childLimit->getStartFortune();
        // 年龄
        $this->assertEquals(8, $fortune->getAge());
    }

    function test17()
    {
        $eightChar = new EightChar(
            SixtyCycle::fromName('己丑'),
            SixtyCycle::fromName('戊辰'),
            SixtyCycle::fromName('戊辰'),
            SixtyCycle::fromName('甲子')
        );
        $this->assertEquals('丁丑', $eightChar->getOwnSign()->getName());
    }

    function test18()
    {
        $eightChar = new EightChar(
            SixtyCycle::fromName('戊戌'),
            SixtyCycle::fromName('庚申'),
            SixtyCycle::fromName('丁亥'),
            SixtyCycle::fromName('丙午')
        );
        $this->assertEquals('乙卯', $eightChar->getOwnSign()->getName());
    }

    function test19()
    {
        $eightChar = new EightChar(
            SixtyCycle::fromName('甲子'),
            SixtyCycle::fromName('壬申'),
            SixtyCycle::fromName('庚子'),
            SixtyCycle::fromName('乙亥')
        );
        $this->assertEquals('甲戌', $eightChar->getOwnSign()->getName());
    }

    function test20()
    {
        $eightChar = ChildLimit::fromSolarTime(SolarTime::fromYmdHms(2024, 1, 29, 9, 33, 0), Gender::MAN)->getEightChar();
        $this->assertEquals('癸亥', $eightChar->getOwnSign()->getName());
        $this->assertEquals('己未', $eightChar->getBodySign()->getName());
    }

    function test21()
    {
        $eightChar = new EightChar(
            SixtyCycle::fromName('辛亥'),
            SixtyCycle::fromName('乙未'),
            SixtyCycle::fromName('庚子'),
            SixtyCycle::fromName('甲辰')
        );
        $this->assertEquals('庚子', $eightChar->getBodySign()->getName());
    }

    function test22()
    {
        $this->assertEquals('丙寅', ChildLimit::fromSolarTime(SolarTime::fromYmdHms(1990, 1, 27, 0, 0, 0), Gender::MAN)->getEightChar()->getBodySign()->getName());
    }

    function test23()
    {
        $this->assertEquals('甲戌', ChildLimit::fromSolarTime(SolarTime::fromYmdHms(2019, 3, 7, 8, 0, 0), Gender::MAN)->getEightChar()->getOwnSign()->getName());
    }

    function test24()
    {
        $this->assertEquals('丁丑', ChildLimit::fromSolarTime(SolarTime::fromYmdHms(2019, 3, 27, 2, 0, 0), Gender::MAN)->getEightChar()->getOwnSign()->getName());
    }

    function test25()
    {
        $this->assertEquals('丙寅', LunarHour::fromYmdHms(1994, 5, 20, 18, 0, 0)->getEightChar()->getOwnSign()->getName());
    }

    function test26()
    {
        $this->assertEquals('己丑', SolarTime::fromYmdHms(1986, 5, 29, 13, 37, 0)->getLunarHour()->getEightChar()->getBodySign()->getName());
    }

    function test27()
    {
        $this->assertEquals('乙丑', SolarTime::fromYmdHms(1994, 12, 6, 2, 0, 0)->getLunarHour()->getEightChar()->getBodySign()->getName());
    }

    function test28()
    {
        $eightChar = new EightChar(
            SixtyCycle::fromName('辛亥'),
            SixtyCycle::fromName('丁酉'),
            SixtyCycle::fromName('丙午'),
            SixtyCycle::fromName('癸巳')
        );
        $this->assertEquals('辛卯', $eightChar->getOwnSign()->getName());
    }

    function test29()
    {
        $eightChar = new EightChar('丙寅', '庚寅', '辛卯', '壬辰');
        $this->assertEquals('己亥', $eightChar->getOwnSign()->getName());
        $this->assertEquals('乙未', $eightChar->getBodySign()->getName());
    }

    function test30()
    {
        $eightChar = new EightChar('壬子', '辛亥', '壬戌', '乙巳');
        $this->assertEquals('乙巳', $eightChar->getBodySign()->getName());
    }

    function test31()
    {
        // 采用元亨利贞的起运算法
        ChildLimit::$provider = new China95ChildLimitProvider();
        // 童限
        $childLimit = ChildLimit::fromSolarTime(SolarTime::fromYmdHms(1986, 5, 29, 13, 37, 0), Gender::MAN);
        // 童限年数
        $this->assertEquals(2, $childLimit->getYearCount());
        // 童限月数
        $this->assertEquals(7, $childLimit->getMonthCount());
        // 童限日数
        $this->assertEquals(0, $childLimit->getDayCount());
        // 童限时数
        $this->assertEquals(0, $childLimit->getHourCount());
        // 童限分数
        $this->assertEquals(0, $childLimit->getMinuteCount());
        // 童限结束(即开始起运)的公历时刻
        $this->assertEquals('1988年12月29日 13:37:00', $childLimit->getEndTime()->__toString());

        // 为了不影响其他测试用例，恢复默认起运算法
        ChildLimit::$provider = new DefaultChildLimitProvider();
    }

    public function test32()
    {
        $eightChar = new EightChar('丙辰', '丁酉', '丙子', '甲午');
        $solarTimes = $eightChar->getSolarTimes(1900, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('1916年10月6日 12:00:00', '1976年9月21日 12:00:00');
        $this->assertEquals($expected, $actual);
    }

    public function test33()
    {
        $eightChar = new EightChar('壬寅', '庚戌', '己未', '乙亥');
        $solarTimes = $eightChar->getSolarTimes(1900, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('2022年11月2日 22:00:00');
        $this->assertEquals($expected, $actual);
    }

    public function test34()
    {
        $eightChar = new EightChar('己卯', '辛未', '甲戌', '壬申');
        $solarTimes = $eightChar->getSolarTimes(1900, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('1939年8月5日 16:00:00', '1999年7月21日 16:00:00');
        $this->assertEquals($expected, $actual);
    }

    public function test35()
    {
        $eightChar = new EightChar('庚子', '戊子', '己卯', '庚午');
        $solarTimes = $eightChar->getSolarTimes(1900, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('1901年1月1日 12:00:00', '1960年12月17日 12:00:00');
        $this->assertEquals($expected, $actual);
    }

    public function test36()
    {
        $eightChar = new EightChar('庚子', '癸未', '乙丑', '丁亥');
        $solarTimes = $eightChar->getSolarTimes(1900, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('1960年8月5日 22:00:00', '2020年7月21日 22:00:00');
        $this->assertEquals($expected, $actual);
    }

    public function test37()
    {
        $eightChar = SolarTime::fromYmdHms(1999, 6, 7, 9, 11, 0)->getLunarHour()->getEightChar();
        $actual = $eightChar->__toString();

        $expected = '己卯 庚午 庚寅 辛巳';
        $this->assertEquals($expected, $actual);
    }

    public function test38()
    {
        $eightChar = new EightChar('癸卯', '甲寅', '甲寅', '甲子');
        $solarTimes = $eightChar->getSolarTimes(1800, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('1843年2月9日 00:00:00', '2023年2月25日 00:00:00');
        $this->assertEquals($expected, $actual);
    }

    public function test39()
    {
        $eightChar = new EightChar('己亥', '丁丑', '壬寅', '戊申');
        $solarTimes = $eightChar->getSolarTimes(1900, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('1900年1月29日 16:00:00', '1960年1月15日 16:00:00');
        $this->assertEquals($expected, $actual);
    }

    public function test40()
    {
        $eightChar = new EightChar('己亥', '丙子', '癸酉', '庚申');
        $solarTimes = $eightChar->getSolarTimes(1900, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('1959年12月17日 16:00:00');
        $this->assertEquals($expected, $actual);
    }

    public function test41()
    {
        $eightChar = new EightChar('乙亥', '乙酉', '乙酉', '乙酉');
        $solarTimes = $eightChar->getSolarTimes(1000, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('1095年9月23日 18:00:00', '1155年9月8日 18:00:00', '1335年9月23日 18:00:00', '1395年9月8日 18:00:00', '1575年9月23日 18:00:00', '1635年9月18日 18:00:00', '1815年10月5日 18:00:00', '1875年9月20日 18:00:00');
        $this->assertEquals($expected, $actual);
    }

    public function test42()
    {
        $eightChar = new EightChar('癸卯', '乙卯', '丙辰', '丁酉');
        $solarTimes = $eightChar->getSolarTimes(1900, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('1903年3月29日 18:00:00', '1963年3月14日 18:00:00');
        $this->assertEquals($expected, $actual);
    }

    public function test46()
    {
        LunarHour::$provider = new LunarSect2EightCharProvider();

        $eightChar = new EightChar('壬寅', '丙午', '己亥', '丙子');
        $solarTimes = $eightChar->getSolarTimes(1900, 2024);
        $actual = array();
        foreach ($solarTimes as $solarTime) {
            $actual[] = $solarTime->__toString();
        }

        $expected = array('1962年6月30日 23:00:00', '2022年6月15日 23:00:00');
        $this->assertEquals($expected, $actual);

        LunarHour::$provider = new DefaultEightCharProvider();
    }
}
