<?php

use com\tyme\culture\Week;
use com\tyme\lunar\LunarWeek;
use com\tyme\solar\SolarDay;
use com\tyme\solar\SolarMonth;
use com\tyme\solar\SolarWeek;
use PHPUnit\Framework\TestCase;

/**
 * 星期测试
 * @author 6tail
 */
class WeekTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('一', SolarDay::fromYmd(1582, 10, 1)->getWeek()->getName());
    }

    function test1()
    {
        $this->assertEquals('五', SolarDay::fromYmd(1582, 10, 15)->getWeek()->getName());
    }

    function test2()
    {
        $this->assertEquals(2, SolarDay::fromYmd(2023, 10, 31)->getWeek()->getIndex());
    }

    function test3()
    {
        $w = SolarWeek::fromYm(2023, 10, 0, 0);
        $this->assertEquals('第一周', $w->getName());
        $this->assertEquals('2023年10月第一周', $w->__toString());
    }

    function test5()
    {
        $w = SolarWeek::fromYm(2023, 10, 4, 0);
        $this->assertEquals('第五周', $w->getName());
        $this->assertEquals('2023年10月第五周', $w->__toString());
    }

    function test6()
    {
        $w = SolarWeek::fromYm(2023, 10, 5, 1);
        $this->assertEquals('第六周', $w->getName());
        $this->assertEquals('2023年10月第六周', $w->__toString());
    }

    function test7()
    {
        $w = SolarWeek::fromYm(2023, 10, 0, 0)->next(4);
        $this->assertEquals('第五周', $w->getName());
        $this->assertEquals('2023年10月第五周', $w->__toString());
    }

    function test8()
    {
        $w = SolarWeek::fromYm(2023, 10, 0, 0)->next(5);
        $this->assertEquals('第二周', $w->getName());
        $this->assertEquals('2023年11月第二周', $w->__toString());
    }

    function test9()
    {
        $w = SolarWeek::fromYm(2023, 10, 0, 0)->next(-1);
        $this->assertEquals('第五周', $w->getName());
        $this->assertEquals('2023年9月第五周', $w->__toString());
    }

    function test10()
    {
        $w = SolarWeek::fromYm(2023, 10, 0, 0)->next(-5);
        $this->assertEquals('第一周', $w->getName());
        $this->assertEquals('2023年9月第一周', $w->__toString());
    }

    function test11()
    {
        $w = SolarWeek::fromYm(2023, 10, 0, 0)->next(-6);
        $this->assertEquals('第四周', $w->getName());
        $this->assertEquals('2023年8月第四周', $w->__toString());
    }

    function test12()
    {
        $solar = SolarDay::fromYmd(1582, 10, 1);
        $this->assertEquals(1, $solar->getWeek()->getIndex());
    }

    function test13()
    {
        $solar = SolarDay::fromYmd(1582, 10, 15);
        $this->assertEquals(5, $solar->getWeek()->getIndex());
    }

    function test14()
    {
        $solar = SolarDay::fromYmd(1129, 11, 17);
        $this->assertEquals(0, $solar->getWeek()->getIndex());
    }

    function test15()
    {
        $solar = SolarDay::fromYmd(1129, 11, 1);
        $this->assertEquals(5, $solar->getWeek()->getIndex());
    }

    function test16()
    {
        $solar = SolarDay::fromYmd(8, 11, 1);
        $this->assertEquals(4, $solar->getWeek()->getIndex());
    }

    function test17()
    {
        $solar = SolarDay::fromYmd(1582, 9, 30);
        $this->assertEquals(0, $solar->getWeek()->getIndex());
    }

    function test18()
    {
        $solar = SolarDay::fromYmd(1582, 1, 1);
        $this->assertEquals(1, $solar->getWeek()->getIndex());
    }

    function test19()
    {
        $solar = SolarDay::fromYmd(1500, 2, 29);
        $this->assertEquals(6, $solar->getWeek()->getIndex());
    }

    function test20()
    {
        $solar = SolarDay::fromYmd(9865, 7, 26);
        $this->assertEquals(3, $solar->getWeek()->getIndex());
    }

    function test21()
    {
        $week = LunarWeek::fromYm(2023, 1, 0, 2);
        $this->assertEquals('农历癸卯年正月第一周', $week->__toString());
        $this->assertEquals('农历壬寅年十二月廿六', $week->getFirstDay()->__toString());
    }

    function test22()
    {
        $week = SolarWeek::fromYm(2023, 1, 0, 2);
        $this->assertEquals('2023年1月第一周', $week->__toString());
        $this->assertEquals('2022年12月27日', $week->getFirstDay()->__toString());
    }

    function test23()
    {
        $start = 0;
        $week = SolarWeek::fromYm(2024, 2, 2, $start);
        $this->assertEquals('2024年2月第三周', $week->__toString());
        $this->assertEquals(6, $week->getIndexInYear());

        $week = SolarDay::fromYmd(2024, 2, 11)->getSolarWeek($start);
        $this->assertEquals('2024年2月第三周', $week->__toString());

        $week = SolarDay::fromYmd(2024, 2, 17)->getSolarWeek($start);
        $this->assertEquals('2024年2月第三周', $week->__toString());

        $week = SolarDay::fromYmd(2024, 2, 10)->getSolarWeek($start);
        $this->assertEquals('2024年2月第二周', $week->__toString());

        $week = SolarDay::fromYmd(2024, 2, 18)->getSolarWeek($start);
        $this->assertEquals('2024年2月第四周', $week->__toString());
    }

    public function show()
    {
        $this->getMonthDayList(2024, 6, 20, 1);
    }


    /**
     * 获取日历
     */
    protected function getMonthDayList(int $yyyy, int $mmmm, int $dddd, int $weekStart = 0): array
    {
        $month = SolarMonth::fromYm($yyyy, $mmmm);
        $weekHeads = [];
        $w = Week::fromIndex($weekStart);
        for ($i = 0; $i < 7; $i++) {
            $weekHeads[] = [
                'isWeekend' => $w->getIndex() === 6 || $w->getIndex() === 0,
                'name' => $w->getName()
            ];
            $w = $w->next(1);
        }
        $weeks = [];
        $monthWeeks = $month->getWeeks($weekStart);
        for ($i = 0, $j = count($monthWeeks); $i < $j; $i++) {
            $days = [];
            $weekDays = $monthWeeks[$i]->getDays();
            for ($x = 0, $y = count($weekDays); $x < $y; $x++) {
                $solarDay = $weekDays[$x];

                $lunar_day  = $solarDay->getLunarDay();

                $holiday = $solarDay->getLegalHoliday();
                $weekIndex = $solarDay->getWeek()->getIndex();
                $weekend = $weekIndex === 6 || $weekIndex === 0;
                if ($holiday && $holiday->isWork()) {
                    $weekend = false;
                }

                $text = $lunar_day->getName();

                $f = $solarDay->getFestival();
                if ($f) {
                    $text = $f->getName();
                }

                $f = $lunar_day->getFestival();
                if ($f) {
                    $text = $f->getName();
                }

                if (1 === $lunar_day->getDay()) {
                    $lunarMonth = $lunar_day->getMonth();
                    $text = $lunarMonth->getName();
                    if (1 === $lunarMonth->getMonthWithLeap()) {
                        $text = $lunarMonth->getYear()->getSixtyCycle()->getName() . '年' . $text;
                    }
                }

                $jq = $solarDay->getTermDay();
                if ($jq->getDayIndex() == 0) {
                    $text = $jq->getName();
                }
                $days[] = [
                    'date' => $solarDay->__toString(),
                    'day' => $solarDay->getDay(),
                    'solar_month' => $solarDay->getMonth(),
                    'month' => $month,
                    'holiday' => $holiday ? ['isWork' => $holiday->isWork()] : null,
                    'isCurrentMonth' => $solarDay->getMonth()->equals($month),
                    'isToday' => $solarDay->getDay() == 20 && $solarDay->getMonth()->getMonth() == 6,
                    'isCurrentDay' => $solarDay->getDay() == 20 && $solarDay->getMonth()->equals($month),
                    'isWeekend' => $weekend,
                    'text' => $text
                ];
            }
            $weeks[] = ['days' => $days];
        }
        return [
            'month' => [
                'name' => $month->__toString(),
                'weeks' => $weeks
            ],
            'weeks' => $weekHeads
        ];
    }

    function test24() {
        $this->show();
        $this->assertTrue(!false);
    }
}
